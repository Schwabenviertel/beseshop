import { NextRequest, NextResponse } from "next/server";
import { v4 as uuidv4 } from "uuid";
import { prisma } from "@/lib/prisma";
import { getCurrentUser } from "@/lib/auth";
import { Prisma } from "@prisma/client";

interface CheckoutItem {
  productId: string;
  quantity: number;
}

interface CheckoutBody {
  checkoutToken: string;
  paymentMethod: string;
  shipping: {
    name: string;
    street: string;
    city: string;
    zip: string;
  };
  items: CheckoutItem[];
}

export async function POST(request: NextRequest) {
  try {
    const user = await getCurrentUser();

    if (!user) {
      return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
    }

    const body: CheckoutBody = await request.json();
    const { checkoutToken, paymentMethod, shipping, items } = body;

    if (!checkoutToken || !paymentMethod || !shipping || !items?.length) {
      return NextResponse.json(
        { error: "Missing required fields" },
        { status: 400 }
      );
    }

    const validPaymentMethods = ["credit_card", "paypal", "bank_transfer", "cash"];
    if (!validPaymentMethods.includes(paymentMethod)) {
      return NextResponse.json(
        { error: "Invalid payment method" },
        { status: 400 }
      );
    }

    const existingOrder = await prisma.order.findUnique({
      where: { checkoutToken },
      include: {
        items: {
          include: {
            product: true,
          },
        },
      },
    });

    if (existingOrder) {
      return NextResponse.json(
        {
          message: "Order already exists",
          order: {
            id: existingOrder.id,
            orderNumber: existingOrder.orderNumber,
            totalCents: existingOrder.totalCents,
            paidAt: existingOrder.paidAt,
            items: existingOrder.items,
          },
        },
        { status: 409 }
      );
    }

    const productIds = items.map((item) => item.productId);
    const products = await prisma.product.findMany({
      where: { id: { in: productIds } },
    });

    if (products.length !== productIds.length) {
      return NextResponse.json(
        { error: "Some products not found" },
        { status: 400 }
      );
    }

    const productMap = new Map(products.map((p) => [p.id, p]));
    let totalCents = 0;

    const orderItemsData = items.map((item) => {
      const product = productMap.get(item.productId);
      if (!product) {
        throw new Error(`Product ${item.productId} not found`);
      }
      totalCents += product.priceCents * item.quantity;
      return {
        productId: item.productId,
        quantity: item.quantity,
        priceCents: product.priceCents,
      };
    });

    const orderNumber = `ORD-${uuidv4().substring(0, 8).toUpperCase()}`;

    try {
      const order = await prisma.$transaction(async (tx) => {
        const newOrder = await tx.order.create({
          data: {
            orderNumber,
            checkoutToken,
            userId: user.userId,
            paymentMethod,
            paidAt: new Date(),
            shippingName: shipping.name,
            shippingStreet: shipping.street,
            shippingCity: shipping.city,
            shippingZip: shipping.zip,
            totalCents,
            items: {
              create: orderItemsData,
            },
          },
          include: {
            items: {
              include: {
                product: true,
              },
            },
          },
        });

        return newOrder;
      });

      return NextResponse.json(
        {
          message: "Order created successfully",
          order: {
            id: order.id,
            orderNumber: order.orderNumber,
            totalCents: order.totalCents,
            paidAt: order.paidAt,
            items: order.items,
          },
        },
        { status: 201 }
      );
    } catch (error) {
      if (
        error instanceof Prisma.PrismaClientKnownRequestError &&
        error.code === "P2002"
      ) {
        const conflictOrder = await prisma.order.findUnique({
          where: { checkoutToken },
          include: {
            items: {
              include: {
                product: true,
              },
            },
          },
        });

        if (conflictOrder) {
          return NextResponse.json(
            {
              message: "Order already exists",
              order: {
                id: conflictOrder.id,
                orderNumber: conflictOrder.orderNumber,
                totalCents: conflictOrder.totalCents,
                paidAt: conflictOrder.paidAt,
                items: conflictOrder.items,
              },
            },
            { status: 409 }
          );
        }
      }

      throw error;
    }
  } catch (error) {
    console.error("Checkout error:", error);
    return NextResponse.json(
      { error: "Internal server error" },
      { status: 500 }
    );
  }
}
