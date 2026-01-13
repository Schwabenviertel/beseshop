"use client";

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ShoppingCart } from "lucide-react";

interface Product {
  id: string;
  name: string;
  description: string;
  priceCents: number;
  image: string;
  sku: string;
}

export default function ProductDetailPage() {
  const params = useParams();
  const router = useRouter();
  const [product, setProduct] = useState<Product | null>(null);
  const [quantity, setQuantity] = useState(1);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("/api/products")
      .then((res) => res.json())
      .then((data) => {
        const foundProduct = data.products.find(
          (p: Product) => p.id === params.id
        );
        setProduct(foundProduct || null);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [params.id]);

  const addToCart = () => {
    if (!product) return;

    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const existingItem = cart.find((item: any) => item.productId === product.id);

    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      cart.push({
        productId: product.id,
        name: product.name,
        priceCents: product.priceCents,
        quantity,
        image: product.image,
      });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    router.push("/cart");
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

  if (!product) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <Card className="max-w-md">
          <CardContent className="py-12 text-center">
            <p className="text-xl text-muted-foreground">Bese net gfunde!</p>
          </CardContent>
        </Card>
      </div>
    );
  }

  return (
    <div className="min-h-screen">
      <div className="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <Card className="overflow-hidden">
          <CardContent className="p-0">
            <div className="grid md:grid-cols-2 gap-0">
              <div className="relative aspect-square bg-muted">
                <Image
                  src={product.image}
                  alt={product.name}
                  fill
                  className="object-cover"
                  sizes="(max-width: 768px) 100vw, 50vw"
                  priority
                />
              </div>
              <div className="p-8 lg:p-12 space-y-6 flex flex-col justify-center">
                <div>
                  <p className="text-sm text-muted-foreground mb-2">Artikel-Nr.: {product.sku}</p>
                  <h1 className="text-3xl font-bold mb-4">{product.name}</h1>
                  <p className="text-muted-foreground leading-relaxed">{product.description}</p>
                </div>
                
                <div className="border-t border-b py-6">
                  <div className="flex items-baseline gap-2">
                    <span className="text-4xl font-bold">
                      €{(product.priceCents / 100).toFixed(2)}
                    </span>
                  </div>
                  <p className="text-sm text-muted-foreground mt-2">Inkl. MwSt.</p>
                </div>
                
                <div className="space-y-4">
                  <div className="space-y-2">
                    <Label htmlFor="quantity">Wieviel brauchsch?</Label>
                    <Input
                      id="quantity"
                      type="number"
                      min="1"
                      value={quantity}
                      onChange={(e) => setQuantity(parseInt(e.target.value) || 1)}
                      className="w-32"
                    />
                  </div>
                  <Button 
                    onClick={addToCart} 
                    className="w-full gap-2" 
                    size="lg"
                  >
                    <ShoppingCart className="h-4 w-4" />
                    In'd Wagrekorb noi
                  </Button>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
