"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import Link from "next/link";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { CheckCircle } from "lucide-react";

interface OrderItem {
  id: string;
  quantity: number;
  priceCents: number;
  product: {
    id: string;
    name: string;
    sku: string;
  };
}

interface Order {
  id: string;
  orderNumber: string;
  totalCents: number;
  paidAt: string | null;
  shippingName: string;
  shippingStreet: string;
  shippingCity: string;
  shippingZip: string;
  items: OrderItem[];
  createdAt: string;
}

export default function OrderConfirmationPage() {
  const params = useParams();
  const [order, setOrder] = useState<Order | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch(`/api/orders/${params.orderNumber}`)
      .then((res) => {
        if (!res.ok) throw new Error("Order not found");
        return res.json();
      })
      .then((data) => {
        setOrder(data.order);
        setLoading(false);
      })
      .catch((err) => {
        setError(err.message);
        setLoading(false);
      });
  }, [params.orderNumber]);

  const downloadReceipt = () => {
    if (!order) return;

    const receipt = {
      orderNumber: order.orderNumber,
      date: new Date(order.createdAt).toLocaleDateString("de-DE"),
      items: order.items.map((item) => ({
        product: item.product.name,
        sku: item.product.sku,
        quantity: item.quantity,
        priceEur: (item.priceCents / 100).toFixed(2),
        totalEur: ((item.priceCents * item.quantity) / 100).toFixed(2),
      })),
      shipping: {
        name: order.shippingName,
        address: `${order.shippingStreet}, ${order.shippingZip} ${order.shippingCity}`,
      },
      totalEur: (order.totalCents / 100).toFixed(2),
      paidAt: order.paidAt
        ? new Date(order.paidAt).toLocaleString("de-DE")
        : null,
    };

    const blob = new Blob([JSON.stringify(receipt, null, 2)], {
      type: "application/json",
    });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `receipt-${order.orderNumber}.json`;
    a.click();
    URL.revokeObjectURL(url);
  };

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="text-2xl font-semibold">Moment mol, mir laden...</div>
        </div>
      </div>
    );
  }

  if (error || !order) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <Card className="max-w-md">
          <CardContent className="py-12 text-center">
            <p className="text-destructive mb-4 text-xl font-semibold">
              {error || "Bschtelligung net gfunde!"}
            </p>
            <Link href="/products">
              <Button size="lg">Zruck zum Shop</Button>
            </Link>
          </CardContent>
        </Card>
      </div>
    );
  }

  return (
    <div className="min-h-screen">
      <div className="border-b bg-green-50">
        <div className="mx-auto max-w-4xl px-6 py-12 lg:px-8">
          <div className="text-center">
            <div className="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
              <CheckCircle className="h-8 w-8 text-green-600" />
            </div>
            <h1 className="text-4xl font-bold tracking-tight sm:text-5xl">
              Dangschee!
            </h1>
            <p className="mt-4 text-lg text-muted-foreground">
              Bschtellnummer: <span className="font-semibold">{order.orderNumber}</span>
            </p>
            <p className="mt-2 text-muted-foreground">
              Dei Kehrwoch-Bese isch unterwegs!
            </p>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-6 py-8 lg:px-8">
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Dei Bschtelligung</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
              {order.items.map((item) => (
                <div
                  key={item.id}
                  className="flex justify-between border-b pb-4 last:border-0"
                >
                  <div>
                    <p className="font-semibold">{item.product.name}</p>
                    <p className="text-sm text-muted-foreground">
                      {item.quantity} × €{(item.priceCents / 100).toFixed(2)}
                    </p>
                  </div>
                  <p className="font-semibold">
                    €{((item.priceCents * item.quantity) / 100).toFixed(2)}
                  </p>
                </div>
              ))}
              <div className="flex justify-between border-t pt-4 text-lg font-semibold">
                <span>Gsamt</span>
                <span>€{(order.totalCents / 100).toFixed(2)}</span>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Lieferadress</CardTitle>
            </CardHeader>
            <CardContent>
              <p className="font-semibold">{order.shippingName}</p>
              <p className="text-muted-foreground">{order.shippingStreet}</p>
              <p className="text-muted-foreground">
                {order.shippingZip} {order.shippingCity}
              </p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Zahlung</CardTitle>
            </CardHeader>
            <CardContent>
              {order.paidAt ? (
                <div className="flex items-center gap-2">
                  <CheckCircle className="h-5 w-5 text-green-600" />
                  <p className="font-medium">
                    Zahlt am {new Date(order.paidAt).toLocaleString("de-DE")}
                  </p>
                </div>
              ) : (
                <p className="text-muted-foreground">Nomme ausstehend</p>
              )}
            </CardContent>
          </Card>

          <div className="flex flex-col gap-4 sm:flex-row">
            <Button 
              onClick={downloadReceipt} 
              variant="outline" 
              size="lg"
              className="flex-1"
            >
              Rechnung runterlade
            </Button>
            <Link href="/products" className="flex-1">
              <Button size="lg" className="w-full">
                Weider oikaufe
              </Button>
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
