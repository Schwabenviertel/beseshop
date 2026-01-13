import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { ChocolateLogo } from "@/components/chocolate-logo";
import { ThemeProvider } from "@/components/theme-provider";
import { Navigation } from "@/components/navigation";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Kehrwoch Bese-Shop - Hasch dei Kehrwoch scho gmacht?",
  description: "Premium-Bese für dei schwäbische Kehrwoch! Handgmachte Qualität aus'm Schwarzwald",
};

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const user = await getCurrentUser();

  return (
    <html lang="de" suppressHydrationWarning>
      <body className={inter.className}>
        <ThemeProvider>
          <Navigation user={user} />
          
          <main>{children}</main>
          
          <footer className="border-t border-border mt-20 bg-muted/30">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
              <div className="grid md:grid-cols-3 gap-8 text-sm">
                <div>
                  <div className="flex items-center gap-2 mb-3">
                    <div className="text-2xl">🧹</div>
                    <div className="font-bold text-foreground text-lg">
                      Kehrwoch Bese-Shop
                    </div>
                  </div>
                  <p className="text-sm text-muted-foreground leading-relaxed">
                    Premium-Bese aus Baden-Württemberg - mit Lieb handgmacht! Für d'Kehrwoch gibt's nix Besseres!
                  </p>
                </div>
                
                <div>
                  <h3 className="font-bold text-foreground mb-3 text-base">Shop</h3>
                  <ul className="space-y-2 text-sm text-muted-foreground">
                    <li><Link href="/products" className="hover:text-primary transition-colors font-medium">Alli Bese</Link></li>
                    <li><Link href="/cart" className="hover:text-primary transition-colors font-medium">Wagrekorb</Link></li>
                  </ul>
                </div>
                
                <div>
                  <h3 className="font-bold text-foreground mb-3 text-base">Konto</h3>
                  <ul className="space-y-2 text-sm text-muted-foreground">
                    <li><Link href="/login" className="hover:text-primary transition-colors font-medium">Omelda</Link></li>
                    <li><Link href="/register" className="hover:text-primary transition-colors font-medium">Regischtriere</Link></li>
                  </ul>
                </div>
              </div>
              
              <div className="mt-8 pt-8 border-t border-border text-center">
                <p className="text-sm text-muted-foreground">
                  © {new Date().getFullYear()} Kehrwoch Bese-Shop - Schaffe, schaffe, Häusle butze! 🏡
                </p>
              </div>
            </div>
          </footer>
        </ThemeProvider>
      </body>
    </html>
  );
}
