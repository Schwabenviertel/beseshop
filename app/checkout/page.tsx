"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { v4 as uuidv4 } from "uuid";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

interface CartItem {
  productId: string;
  name: string;
  priceCents: number;
  quantity: number;
}

export default function CheckoutPage() {
  const router = useRouter();
  const [cart, setCart] = useState<CartItem[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [shipping, setShipping] = useState({
    name: "",
    street: "",
    city: "",
    zip: "",
  });
  const [paymentMethod, setPaymentMethod] = useState("credit_card");

  useEffect(() => {
    const storedCart = JSON.parse(localStorage.getItem("cart") || "[]");
    setCart(storedCart);

    if (storedCart.length === 0) {
      router.push("/cart");
    }
  }, [router]);

  const total = cart.reduce(
    (sum, item) => sum + item.priceCents * item.quantity,
    0
  );

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    const checkoutToken = uuidv4();

    try {
      const response = await fetch("/api/cart/checkout", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          checkoutToken,
          paymentMethod,
          shipping,
          items: cart.map((item) => ({
            productId: item.productId,
            quantity: item.quantity,
          })),
        }),
      });

      const data = await response.json();

      if (!response.ok && response.status !== 409) {
        setError(data.error || "Checkout failed");
        setLoading(false);
        return;
      }

      localStorage.removeItem("cart");
      router.push(`/orders/${data.order.orderNumber}`);
    } catch (err) {
      setError("An error occurred. Please try again.");
      setLoading(false);
    }
  };

  if (cart.length === 0) {
    return null;
  }

  return (
    <div className="min-h-screen">
      <div className="border-b">
        <div className="mx-auto max-w-7xl px-6 py-12 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h1 className="text-4xl font-bold tracking-tight sm:text-5xl">
              Jetzat zahle
            </h1>
            <p className="mt-4 text-lg text-muted-foreground">
              Fascht gschafft - nomme no zahle ond dann isch dr Bese dein
            </p>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <div className="grid gap-8 lg:grid-cols-2">
          <div>
            <Card>
              <CardHeader>
                <CardTitle>Lieferadress & Zahlung</CardTitle>
              </CardHeader>
            <CardContent className="pt-6">
            <form onSubmit={handleSubmit} className="space-y-6">
              {error && (
                <div className="rounded-lg bg-destructive/15 px-4 py-3 text-sm text-destructive">
                  {error}
                </div>
              )}
              <div className="space-y-2">
                <Label htmlFor="name">Dein Name</Label>
                <Input
                  id="name"
                  value={shipping.name}
                  onChange={(e) =>
                    setShipping({ ...shipping, name: e.target.value })
                  }
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="street">Schtraß & Hausnummer</Label>
                <Input
                  id="street"
                  value={shipping.street}
                  onChange={(e) =>
                    setShipping({ ...shipping, street: e.target.value })
                  }
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="city">Schtadt</Label>
                <Input
                  id="city"
                  value={shipping.city}
                  onChange={(e) =>
                    setShipping({ ...shipping, city: e.target.value })
                  }
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="zip">Postleitzahl</Label>
                <Input
                  id="zip"
                  value={shipping.zip}
                  onChange={(e) =>
                    setShipping({ ...shipping, zip: e.target.value })
                  }
                  required
                />
              </div>
              <div className="space-y-4">
                <Label>Wie willsch zahle?</Label>
                
                <label className={`flex cursor-pointer items-start gap-3 rounded-lg border p-4 transition-all ${paymentMethod === "credit_card" ? "border-primary bg-accent" : "hover:bg-accent/50"}`}>
                  <input
                    type="radio"
                    name="payment"
                    value="credit_card"
                    checked={paymentMethod === "credit_card"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <div className="font-medium">Kreditkart</div>
                    <div className="mt-1 text-xs text-muted-foreground">
                      Demo: 4242 4242 4242 4242
                    </div>
                  </div>
                </label>

                <label className={`flex cursor-pointer items-start gap-3 rounded-lg border p-4 transition-all ${paymentMethod === "paypal" ? "border-primary bg-accent" : "hover:bg-accent/50"}`}>
                  <input
                    type="radio"
                    name="payment"
                    value="paypal"
                    checked={paymentMethod === "paypal"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <div className="font-medium">PayPal</div>
                    <div className="mt-1 text-xs text-muted-foreground">
                      Demo: schwabe@sparsam.de
                    </div>
                  </div>
                </label>

                <label className={`flex cursor-pointer items-start gap-3 rounded-lg border p-4 transition-all ${paymentMethod === "bank_transfer" ? "border-primary bg-accent" : "hover:bg-accent/50"}`}>
                  <input
                    type="radio"
                    name="payment"
                    value="bank_transfer"
                    checked={paymentMethod === "bank_transfer"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <div className="font-medium">Überweisung</div>
                    <div className="mt-1 text-xs text-muted-foreground">
                      Demo IBAN: DE89 3704 0044 0532 0130 00
                    </div>
                  </div>
                </label>

                <label className={`flex cursor-pointer items-start gap-3 rounded-lg border p-4 transition-all ${paymentMethod === "cash" ? "border-primary bg-accent" : "hover:bg-accent/50"}`}>
                  <input
                    type="radio"
                    name="payment"
                    value="cash"
                    checked={paymentMethod === "cash"}
                    onChange={(e) => setPaymentMethod(e.target.value)}
                    className="mt-1"
                  />
                  <div className="flex-1">
                    <div className="font-medium">Nachnahme</div>
                    <div className="mt-1 text-xs text-muted-foreground">
                      Bar bei Lieferung
                    </div>
                  </div>
                </label>
              </div>
              <Button 
                type="submit" 
                className="w-full" 
                size="lg"
                disabled={loading}
              >
                {loading ? "Wird bearbeitet..." : "Jetzat kaufe"}
              </Button>
            </form>
          </CardContent>
          </Card>
          </div>

          <div>
            <Card className="sticky top-4">
              <CardHeader>
                <CardTitle>Dei Bschtelligung</CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                {cart.map((item) => (
                  <div
                    key={item.productId}
                    className="flex justify-between gap-4 border-b pb-4 last:border-0"
                  >
                    <div className="flex-1">
                      <p className="font-medium">{item.name}</p>
                      <p className="mt-1 text-sm text-muted-foreground">
                        {item.quantity}x à €{(item.priceCents / 100).toFixed(2)}
                      </p>
                    </div>
                    <p className="font-semibold">
                      €{((item.priceCents * item.quantity) / 100).toFixed(2)}
                    </p>
                  </div>
                ))}
                <div className="border-t pt-4">
                  <div className="flex justify-between text-lg font-semibold">
                    <span>Gsamt</span>
                    <span>€{(total / 100).toFixed(2)}</span>
                  </div>
                  <div className="mt-4 rounded-lg bg-muted p-4">
                    <p className="text-sm text-muted-foreground">
                      Kostenloser Versand innerhalb Deitschland
                    </p>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}
