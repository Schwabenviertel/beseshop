import Link from "next/link";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Sparkles, Star } from "lucide-react";

export default function HomePage() {
  return (
    <div className="min-h-screen">
      <section className="relative">
        <div className="absolute inset-0 -z-10 bg-gradient-to-b from-green-50 to-background"></div>
        
        <div className="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h1 className="text-4xl font-bold tracking-tight sm:text-6xl">
              Hasch dei Kehrwoch scho gmacht?
            </h1>
            <p className="mt-6 text-lg leading-8 text-muted-foreground">
              Premium-Bese für dei Kehrwoch – Handgmacht mit Lieb aus'm Ländle. 
              Deutsche Wertarbeit aus Baden-Württemberg.
            </p>
            <div className="mt-10 flex items-center justify-center gap-x-6">
              <Link href="/products">
                <Button size="lg" className="gap-2">
                  Bese aussuache
                  <Sparkles className="h-4 w-4" />
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </section>

      <section className="py-24 sm:py-32">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h2 className="text-3xl font-bold tracking-tight sm:text-4xl">
              Warum bei uns kaufe?
            </h2>
            <p className="mt-4 text-lg text-muted-foreground">
              Qualität und Nachhaltigkeit aus'm Ländle
            </p>
          </div>
          <div className="mx-auto mt-16 max-w-7xl">
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
              <Card>
                <CardContent className="pt-6">
                  <div className="flex items-start gap-4">
                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-2xl">
                      🌳
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold">Nachhaltig halt!</h3>
                      <p className="mt-2 text-sm text-muted-foreground">
                        Natürliche Materiale aus'm Schwarzwald - net so'n Plastik-Krempel!
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
              
              <Card>
                <CardContent className="pt-6">
                  <div className="flex items-start gap-4">
                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100 text-2xl">
                      🤲
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold">Handgmacht</h3>
                      <p className="mt-2 text-sm text-muted-foreground">
                        Mit schwäbischer Sorgfalt hergstellt - jeder Bese a Unikat!
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
              
              <Card>
                <CardContent className="pt-6">
                  <div className="flex items-start gap-4">
                    <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-2xl">
                      ⚡
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold">Flink wie dr Wind</h3>
                      <p className="mt-2 text-sm text-muted-foreground">
                        Pünktlich vor deiner Kehrwoch - des kannsch dr drauf verlassa!
                      </p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
