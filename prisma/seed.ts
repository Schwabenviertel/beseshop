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
      image: "/brooms/broom-01.jpg",
    },
    {
      sku: "BROOM-002",
      name: "Kehrwoch-Meischter Pro",
      description:
        "Extra schtarke Borschte für hartnäckigen Schmutz. Des isch koi Schpielzeug - des isch a Arbeitsgerät! Schaffe, schaffe, Bese kaufe!",
      priceCents: 3299,
      image: "/brooms/broom-02.jpg",
    },
    {
      sku: "BROOM-003",
      name: "Schpätzle-Feger Deluxe",
      description:
        "So woich wie Omas Schpätzle, aber fegt wie dr Teufel! Premium Naturborschte - des lohnt sich!",
      priceCents: 2899,
      image: "/brooms/broom-03.jpg",
    },
    {
      sku: "BROOM-004",
      name: "Geizhals Schpar-Bese",
      description:
        "Net teuer aber guad! Für schparsame Schwoba. Kuschd wenig, bringd aber viel! Des basst scho!",
      priceCents: 1999,
      image: "/brooms/broom-04.jpg",
    },
    {
      sku: "BROOM-005",
      name: "Häusle-Bauer Hartholz",
      description:
        "Massives Hartholz aus'm Schwarzwald. Hält länger als dei Häusle! Solid ond zuverlässig - echt schwäbisch halt!",
      priceCents: 3599,
      image: "/brooms/broom-05.jpg",
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
