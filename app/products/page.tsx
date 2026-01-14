"use client";

import { useEffect, useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Star } from "lucide-react";

type Product = {
  id: string;
  sku: string;
  name: string;
  description: string;
  priceCents: number;
  image: string;
  createdAt: string;
  updatedAt: string;
};

export default function ProductsPage() {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState("");
  const [sortBy, setSortBy] = useState("name");
  const [error, setError] = useState(false);

  useEffect(() => {
    fetch("/api/products")
      .then((res) => res.json())
      .then((data) => {
        setProducts(data.products || []);
        setFilteredProducts(data.products || []);
        setLoading(false);
      })
      .catch(() => {
        setError(true);
        setLoading(false);
        setProducts([]);
        setFilteredProducts([]);
      });
  }, []);

  useEffect(() => {
    let filtered = [...products];

    if (searchQuery) {
      filtered = filtered.filter(
        (p) =>
          p.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
          p.description.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }

    switch (sortBy) {
      case "name":
        filtered.sort((a, b) => a.name.localeCompare(b.name));
        break;
      case "price-low":
        filtered.sort((a, b) => a.priceCents - b.priceCents);
        break;
      case "price-high":
        filtered.sort((a, b) => b.priceCents - a.priceCents);
        break;
    }

    setFilteredProducts(filtered);
  }, [searchQuery, sortBy, products]);

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <div className="text-center">
          <div className="text-2xl font-semibold">Moment mol, mir laden...</div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <div className="bg-card border-b dark:border-gray-800">
        <div className="mx-auto max-w-7xl px-6 py-12 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h1 className="text-4xl font-bold tracking-tight uppercase sm:text-5xl">
              Onsre Bese
            </h1>
            <p className="mt-4 text-lg text-muted-foreground">
              Handgmachte Qualität aus Baden-Württemberg
            </p>
          </div>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-6 py-8 lg:px-8">
        <div className="mb-8 flex flex-col gap-4 sm:flex-row">
          <Input
            type="text"
            placeholder="Bese suache..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="flex-1"
          />
          <Select value={sortBy} onValueChange={setSortBy}>
            <SelectTrigger className="w-full sm:w-[200px]">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="name">Name (A-Z)</SelectItem>
              <SelectItem value="price-low">Preis (billig zuerscht)</SelectItem>
              <SelectItem value="price-high">Preis (teuer zuerscht)</SelectItem>
            </SelectContent>
          </Select>
        </div>

        {filteredProducts.length === 0 ? (
          <div className="py-12 text-center">
            <p className="text-lg text-muted-foreground">
              Koine Bese gfunde. Probier's mol mit ebbes anderem!
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {filteredProducts.map((product) => (
              <Link key={product.id} href={`/products/${product.id}`} className="group">
                <Card className="overflow-hidden bg-card border-0 shadow-lg dark:shadow-none hover:shadow-xl transition-shadow">
                  <div className="relative aspect-square overflow-hidden rounded-2xl bg-muted">
                    <Image
                      src={product.image}
                      alt={product.name}
                      fill
                      className="object-cover transition-transform group-hover:scale-105"
                      sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw"
                    />
                  </div>
                  <div className="p-4">
                    <h3 className="font-bold text-base line-clamp-1 mb-1">{product.name}</h3>
                    <div className="flex items-center gap-1 mb-2">
                      <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                      <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                      <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                      <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                      <Star className="h-4 w-4 fill-gray-300 dark:fill-gray-600 text-gray-300 dark:text-gray-600" />
                      <span className="text-xs text-muted-foreground ml-1">4.5/5</span>
                    </div>
                    <div className="text-xl font-bold">
                      €{(product.priceCents / 100).toFixed(2)}
                    </div>
                  </div>
                </Card>
              </Link>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
