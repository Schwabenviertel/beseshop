"use client";

import Link from "next/link";
import { ShoppingCart, User } from "lucide-react";
import { Button } from "@/components/ui/button";
import { LogoutButton } from "@/components/logout-button";
import { ThemeToggle } from "@/components/theme-toggle";

type NavigationProps = {
  user: { email: string } | null;
};

export function Navigation({ user }: NavigationProps) {
  return (
    <nav className="sticky top-0 z-50 bg-background/95 backdrop-blur-sm border-b border-border shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <div className="flex items-center gap-6">
            <Link href="/" className="flex items-center gap-2 hover:opacity-80 transition-opacity">
              <div className="text-2xl">🧹</div>
              <div className="text-lg font-bold text-foreground">
                Kehrwoch Bese-Shop
              </div>
            </Link>
            
            <nav className="hidden md:flex items-center gap-5">
              <Link href="/products" className="text-base font-semibold text-foreground hover:text-primary transition-colors">
                Onsre Bese
              </Link>
            </nav>
          </div>
          
          <div className="flex items-center gap-3">
            <ThemeToggle />
            {user ? (
              <>
                <div className="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-muted rounded-lg border border-border text-sm">
                  <User className="w-4 h-4 text-muted-foreground" />
                  <span className="text-foreground font-medium">{user.email}</span>
                </div>
                <Link href="/cart">
                  <Button variant="outline" size="default" className="font-semibold">
                    <ShoppingCart className="w-4 h-4 mr-1.5" />
                    <span className="hidden sm:inline">Wagrekorb</span>
                  </Button>
                </Link>
                <LogoutButton />
              </>
            ) : (
              <>
                <Link href="/login">
                  <Button variant="ghost" size="default" className="font-semibold">
                    Omelda
                  </Button>
                </Link>
                <Link href="/register">
                  <Button size="default" className="font-semibold">
                    Regischtriere
                  </Button>
                </Link>
              </>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
