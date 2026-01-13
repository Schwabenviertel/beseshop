import Link from "next/link";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";

export default function HomePage() {
  return (
    <div className="min-h-screen bg-gray-50">
      <section className="bg-gray-100">
        <div className="mx-auto max-w-7xl px-6 py-20 lg:px-8 lg:py-32">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div>
              <h1 className="text-5xl lg:text-6xl font-black tracking-tight leading-tight uppercase">
                Find Bese That Matches Your Kehrwoch
              </h1>
              <p className="mt-6 text-lg text-gray-600 leading-relaxed">
                Entdecke onsri sorgfältig gfertigte Bese, gmacht zum dei Individualität rauszbringe ond dei Kehrwoch perfekt z'mache.
              </p>
              <div className="mt-8">
                <Link href="/products">
                  <Button size="lg" className="bg-black hover:bg-black/90 text-white rounded-full px-12 py-6 text-base">
                    Jetzat oikaufe
                  </Button>
                </Link>
              </div>
              <div className="mt-12 flex flex-wrap gap-8">
                <div>
                  <div className="text-3xl font-bold">10+</div>
                  <div className="text-sm text-gray-600">Premium Bese</div>
                </div>
                <div className="h-12 w-px bg-gray-300"></div>
                <div>
                  <div className="text-3xl font-bold">100%</div>
                  <div className="text-sm text-gray-600">Handgmacht</div>
                </div>
                <div className="h-12 w-px bg-gray-300"></div>
                <div>
                  <div className="text-3xl font-bold">200+</div>
                  <div className="text-sm text-gray-600">Zfriednig Kunde</div>
                </div>
              </div>
            </div>
            <div className="relative">
              <div className="aspect-square bg-gray-200 rounded-2xl overflow-hidden">
                <img 
                  src="https://images.pexels.com/photos/4239037/pexels-photo-4239037.jpeg?auto=compress&cs=tinysrgb&w=800"
                  alt="Premium Broom"
                  className="w-full h-full object-cover"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-white py-16">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="flex items-center justify-center gap-8 flex-wrap">
            <div className="text-2xl font-bold tracking-tight">SCHWOBA</div>
            <div className="text-2xl font-bold tracking-tight">LÄNDLE</div>
            <div className="text-2xl font-bold tracking-tight">KEHRWOCH</div>
            <div className="text-2xl font-bold tracking-tight">QUALITÄT</div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-white">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-4xl font-bold tracking-tight uppercase">
              Warum bei uns kaufe?
            </h2>
          </div>
          <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
            <Card className="border-0 shadow-none bg-gray-50">
              <CardContent className="pt-8 pb-8">
                <div className="text-center">
                  <div className="text-4xl mb-4">🌳</div>
                  <h3 className="font-bold text-lg mb-2">Nachhaltig halt!</h3>
                  <p className="text-sm text-gray-600">
                    Natürliche Materiale aus'm Schwarzwald - net so'n Plastik-Krempel!
                  </p>
                </div>
              </CardContent>
            </Card>
            
            <Card className="border-0 shadow-none bg-gray-50">
              <CardContent className="pt-8 pb-8">
                <div className="text-center">
                  <div className="text-4xl mb-4">🤲</div>
                  <h3 className="font-bold text-lg mb-2">Handgmacht</h3>
                  <p className="text-sm text-gray-600">
                    Mit schwäbischer Sorgfalt hergstellt - jeder Bese a Unikat!
                  </p>
                </div>
              </CardContent>
            </Card>
            
            <Card className="border-0 shadow-none bg-gray-50">
              <CardContent className="pt-8 pb-8">
                <div className="text-center">
                  <div className="text-4xl mb-4">⚡</div>
                  <h3 className="font-bold text-lg mb-2">Flink wie dr Wind</h3>
                  <p className="text-sm text-gray-600">
                    Pünktlich vor deiner Kehrwoch - des kannsch dr drauf verlassa!
                  </p>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </section>
    </div>
  );
}
