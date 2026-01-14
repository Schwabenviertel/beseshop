import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import Link from "next/link";
import { getCurrentUser } from "@/lib/auth";
import { Navigation } from "@/components/navigation";
import { PromoBanner } from "@/components/promo-banner";
import { ThemeProvider } from "@/components/theme-provider";
import { Mail } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Logo } from "@/components/logo";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "BESE.CO - Premium Schwäbische Bese",
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
          <PromoBanner />
          <Navigation user={user} />
          
          <main>{children}</main>
          
          <section className="bg-gray-100 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
          <div className="max-w-7xl mx-auto">
            <div className="max-w-xl mx-auto text-center">
              <h2 className="text-2xl font-bold mb-6 uppercase tracking-tight">
                Bleib uf'm Laufende über onsre neueschte Angebote
              </h2>
              <form className="flex gap-3 max-w-md mx-auto">
                <div className="relative flex-1">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                  <Input
                    type="email"
                    placeholder="Gib dei E-Mail-Adress o"
                    className="pl-10 bg-white"
                  />
                </div>
                <Button type="submit" className="bg-black hover:bg-black/90 text-white">
                  Omelde
                </Button>
              </form>
            </div>
          </div>
        </section>
        
        <footer className="bg-gray-100 dark:bg-gray-900 border-t dark:border-gray-800">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
              <div>
                <Logo />
                <p className="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-4">
                  Premium-Bese aus Baden-Württemberg - mit Lieb handgmacht!
                </p>
              </div>
              
              <div>
                <h3 className="font-bold mb-3 text-sm uppercase tracking-wider">Shop</h3>
                <ul className="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                  <li><Link href="/products" className="hover:underline">Alli Bese</Link></li>
                  <li><Link href="/cart" className="hover:underline">Wagrekorb</Link></li>
                </ul>
              </div>
              
              <div>
                <h3 className="font-bold mb-3 text-sm uppercase tracking-wider">Konto</h3>
                <ul className="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                  <li><Link href="/login" className="hover:underline">Omelda</Link></li>
                  <li><Link href="/register" className="hover:underline">Regischtriere</Link></li>
                </ul>
              </div>
              
              <div>
                <h3 className="font-bold mb-3 text-sm uppercase tracking-wider">Hilfe</h3>
                <ul className="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                  <li><Link href="#" className="hover:underline">Kontakt</Link></li>
                  <li><Link href="#" className="hover:underline">FAQ</Link></li>
                </ul>
              </div>
            </div>
            
            <div className="pt-8 border-t border-gray-300 dark:border-gray-700 text-center">
              <p className="text-sm text-gray-600 dark:text-gray-400">
                BESE.CO © {new Date().getFullYear()} - Handgmachte Bese aus'm Ländle
              </p>
            </div>
          </div>
        </footer>
        </ThemeProvider>
      </body>
    </html>
  );
}
