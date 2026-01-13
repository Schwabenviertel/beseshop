"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const response = await fetch("/api/auth/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });

      const data = await response.json();

      if (!response.ok) {
        setError(data.error || "Login failed");
        setLoading(false);
        return;
      }

      router.push("/");
      router.refresh();
    } catch (err) {
      setError("An error occurred. Please try again.");
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[80vh] flex items-center justify-center px-4 bg-gradient-to-b from-background to-muted/20">
      <Card className="w-full max-w-md shadow-xl">
        <CardHeader>
          <CardTitle className="text-3xl">Omelda 👋</CardTitle>
          <CardDescription className="text-base">
            Meld di o, damit'd Bese kaufe kannsch!
          </CardDescription>
        </CardHeader>
        <form onSubmit={handleSubmit}>
          <CardContent className="space-y-4">
            {error && (
              <div className="bg-destructive/15 text-destructive px-4 py-3 rounded-lg font-semibold">
                {error}
              </div>
            )}
            <div className="space-y-2">
              <Label htmlFor="email" className="text-base font-semibold">E-Mail</Label>
              <Input
                id="email"
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="h-12"
              />
            </div>
            <div className="space-y-2">
              <Label htmlFor="password" className="text-base font-semibold">Passwort</Label>
              <Input
                id="password"
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                className="h-12"
              />
            </div>
            <div className="text-sm text-muted-foreground bg-muted p-3 rounded-lg">
              <p className="font-semibold mb-1">Test-Konto:</p>
              <p>E-Mail: teacher@example.com</p>
              <p>Passwort: lehrerpass</p>
            </div>
          </CardContent>
          <CardFooter className="flex flex-col gap-4">
            <Button type="submit" className="w-full h-12 text-base font-semibold" disabled={loading}>
              {loading ? "Moment mol..." : "Jetzat omelda!"}
            </Button>
            <p className="text-sm text-center text-muted-foreground">
              No koi Konto?{" "}
              <Link href="/register" className="text-primary hover:underline font-semibold">
                Jetzat regischtriere!
              </Link>
            </p>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
}
