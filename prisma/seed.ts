import { PrismaClient } from "@prisma/client";
import bcrypt from "bcrypt";

const prisma = new PrismaClient();

async function main() {
  const hashedPassword = await bcrypt.hash("lehrerpass", 10);

  const teacher = await prisma.user.upsert({
    where: { email: "teacher@example.com" },
    update: {},
    create: {
      email: "teacher@example.com",
      password: hashedPassword,
      name: "Teacher",
    },
  });

  console.log("Created teacher account:", teacher.email);

  const products = [
    {
      sku: "BROOM-001",
      name: "Der Schwoba-Klassiker",
      description:
        "Der beste Besen für dei Kehrwoch! Aus echtem Reisig vom Ländle. Macht alles blitzeblank - net g'schimpft isch g'lobt gnug!",
      priceCents: 2499,
      image: "https://images.pexels.com/photos/4239037/pexels-photo-4239037.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-002",
      name: "Kehrwoch-Meischter Pro",
      description:
        "Extra schtarke Borschte für hartnäckigen Schmutz. Des isch koi Schpielzeug - des isch a Arbeitsgerät! Schaffe, schaffe, Bese kaufe!",
      priceCents: 3299,
      image: "https://images.pexels.com/photos/4239013/pexels-photo-4239013.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-003",
      name: "Schpätzle-Feger Deluxe",
      description:
        "So woich wie Omas Schpätzle, aber fegt wie dr Teufel! Premium Naturborschte - des lohnt sich!",
      priceCents: 2899,
      image: "https://images.pexels.com/photos/6195275/pexels-photo-6195275.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-004",
      name: "Geizhals Schpar-Bese",
      description:
        "Net teuer aber guad! Für schparsame Schwoba. Kuschd wenig, bringd aber viel! Des basst scho!",
      priceCents: 1999,
      image: "https://images.pexels.com/photos/4099467/pexels-photo-4099467.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-005",
      name: "Häusle-Bauer Hartholz",
      description:
        "Massives Hartholz aus'm Schwarzwald. Hält länger als dei Häusle! Solid ond zuverlässig - echt schwäbisch halt!",
      priceCents: 3599,
      image: "https://images.pexels.com/photos/4239147/pexels-photo-4239147.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-006",
      name: "Viertele-Viere Feierabend-Feger",
      description:
        "Schnell mol vor'm Viertele durchfege! Leicht ond wendig - perfekt für d'Kehrwoch am Freidag!",
      priceCents: 2699,
      image: "https://images.pexels.com/photos/4099427/pexels-photo-4099427.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-007",
      name: "Mauldasche-Bese XXL",
      description:
        "Extrabreit für große Fläche! Wie a Mauldasche - des macht satt... äh, sauber! Effizient ond gründlich!",
      priceCents: 3899,
      image: "https://images.pexels.com/photos/5217889/pexels-photo-5217889.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-008",
      name: "Schaffe-Schaffe Premium",
      description:
        "Für echte Schaffer! Ergonomischer Griff, extra lange Haltbarkeit. Schaffe, schaffe, Häusle butze!",
      priceCents: 4299,
      image: "https://images.pexels.com/photos/4239490/pexels-photo-4239490.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-009",
      name: "Ordnung-Muss-Sei Deluxe",
      description:
        "Ordnung isch des halbe Lebe! Mit dem Bese wird alles perfekt. Deutsche Wertarbeit aus Baden-Württemberg!",
      priceCents: 3499,
      image: "https://images.pexels.com/photos/4239051/pexels-photo-4239051.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
    {
      sku: "BROOM-010",
      name: "Kehrwoch-König Gold",
      description:
        "Des Beschte vom Beschte! Handgfertigt, dreifach versiegelt, lebenslange Garantie. Ond ja, des koschtet ebbes - aber des isch a Inveschtition!",
      priceCents: 5999,
      image: "https://images.pexels.com/photos/4107278/pexels-photo-4107278.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&fit=crop",
    },
  ];

  for (const productData of products) {
    const product = await prisma.product.upsert({
      where: { sku: productData.sku },
      update: productData,
      create: productData,
    });
    console.log("Created/Updated product:", product.name);
  }

  console.log("Seeding completed!");
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
