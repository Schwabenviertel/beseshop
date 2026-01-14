"use client";

import Link from "next/link";
import { useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";

export default function HomePage() {
  const featuredRef = useRef<HTMLDivElement>(null);
  const videoWrapperRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleScroll = () => {
      const scrolled = window.scrollY;
      const windowHeight = window.innerHeight;

      if (videoWrapperRef.current) {
        videoWrapperRef.current.style.transform = `translateY(${scrolled * 0.5}px)`;
      }

      if (featuredRef.current) {
        const rect = featuredRef.current.getBoundingClientRect();
        if (rect.top < windowHeight * 0.75) {
          featuredRef.current.style.opacity = "1";
          featuredRef.current.style.transform = "translateY(0)";
        }
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <div className="min-h-screen bg-background">
      <section className="relative min-h-screen overflow-hidden">
        <div ref={videoWrapperRef} className="absolute inset-0 z-0" style={{ willChange: "transform" }}>
          <div className="absolute inset-0 w-full h-full overflow-hidden">
            <iframe
              className="absolute"
              src="https://www.youtube.com/embed/_OTb5ky7vhM?autoplay=1&mute=1&loop=1&playlist=_OTb5ky7vhM&controls=0&showinfo=0&modestbranding=1&rel=0&iv_load_policy=3&disablekb=1&playsinline=1&start=0&end=50"
              title="Background Video"
              allow="autoplay; encrypted-media"
              frameBorder="0"
              style={{
                pointerEvents: "none",
                position: "absolute",
                top: "50%",
                left: "50%",
                transform: "translate(-50%, -50%) scale(1.5)",
                width: "100vw",
                height: "56.25vw",
                minWidth: "177.78vh",
                minHeight: "100vh",
              }}
            />
          </div>
          <div className="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-background"></div>
        </div>

        <div className="relative z-10 mx-auto max-w-7xl px-6 py-32 lg:px-8 lg:py-48 min-h-screen flex items-center">
          <div className="max-w-3xl">
            <h1 className="text-6xl lg:text-8xl font-black tracking-tight leading-tight uppercase text-white drop-shadow-2xl">
              Find Bese That Matches Your Kehrwoch
            </h1>
            <p className="mt-8 text-xl lg:text-2xl text-gray-100 leading-relaxed drop-shadow-lg">
              Entdecke onsri sorgfältig gfertigte Bese, gmacht zum dei Individualität rauszbringe ond dei Kehrwoch perfekt z'mache.
            </p>
            <div className="mt-12">
              <Link href="/products">
                <Button size="lg" className="bg-white hover:bg-gray-100 text-black rounded-full px-16 py-8 text-lg font-bold shadow-2xl hover:shadow-xl transition-all transform hover:scale-105">
                  Jetzat oikaufe
                </Button>
              </Link>
            </div>
            <div className="mt-16 flex flex-wrap gap-12">
              <div>
                <div className="text-5xl font-bold text-white drop-shadow-lg">10+</div>
                <div className="text-base text-gray-200 mt-2">Premium Bese</div>
              </div>
              <div className="h-16 w-px bg-white/30"></div>
              <div>
                <div className="text-5xl font-bold text-white drop-shadow-lg">100%</div>
                <div className="text-base text-gray-200 mt-2">Handgmacht</div>
              </div>
              <div className="h-16 w-px bg-white/30"></div>
              <div>
                <div className="text-5xl font-bold text-white drop-shadow-lg">200+</div>
                <div className="text-base text-gray-200 mt-2">Zfriednig Kunde</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="bg-background py-24">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="flex items-center justify-center gap-12 flex-wrap">
            <div className="text-3xl font-bold tracking-tight text-foreground">SCHWOBA</div>
            <div className="text-3xl font-bold tracking-tight text-foreground">LÄNDLE</div>
            <div className="text-3xl font-bold tracking-tight text-foreground">KEHRWOCH</div>
            <div className="text-3xl font-bold tracking-tight text-foreground">QUALITÄT</div>
          </div>
        </div>
      </section>

      <section
        ref={featuredRef}
        className="py-32 bg-background transition-all duration-1000 ease-out opacity-0 translate-y-20"
      >
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-5xl lg:text-6xl font-bold tracking-tight uppercase">
              Warum bei uns kaufe?
            </h2>
          </div>
          <div className="grid grid-cols-1 gap-12 md:grid-cols-3">
            <Card className="border-0 shadow-lg dark:shadow-none bg-card hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
              <CardContent className="pt-12 pb-12">
                <div className="text-center">
                  <div className="text-6xl mb-6">🌳</div>
                  <h3 className="font-bold text-2xl mb-4">Nachhaltig halt!</h3>
                  <p className="text-base text-muted-foreground leading-relaxed">
                    Natürliche Materiale aus'm Schwarzwald - net so'n Plastik-Krempel!
                  </p>
                </div>
              </CardContent>
            </Card>
            
            <Card className="border-0 shadow-lg dark:shadow-none bg-card hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
              <CardContent className="pt-12 pb-12">
                <div className="text-center">
                  <div className="text-6xl mb-6">🤲</div>
                  <h3 className="font-bold text-2xl mb-4">Handgmacht</h3>
                  <p className="text-base text-muted-foreground leading-relaxed">
                    Mit schwäbischer Sorgfalt hergstellt - jeder Bese a Unikat!
                  </p>
                </div>
              </CardContent>
            </Card>
            
            <Card className="border-0 shadow-lg dark:shadow-none bg-card hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
              <CardContent className="pt-12 pb-12">
                <div className="text-center">
                  <div className="text-6xl mb-6">⚡</div>
                  <h3 className="font-bold text-2xl mb-4">Flink wie dr Wind</h3>
                  <p className="text-base text-muted-foreground leading-relaxed">
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
