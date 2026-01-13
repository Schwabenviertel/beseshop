"use client";

import { X } from "lucide-react";
import { useState } from "react";

export function PromoBanner() {
  const [isVisible, setIsVisible] = useState(true);

  if (!isVisible) return null;

  return (
    <div className="bg-black text-white py-2 px-4 text-center text-sm relative">
      <p>
        Jetzat omelde ond 20% Rabatt uf dei erschte Beschtelluing kriege!{" "}
        <a href="/register" className="underline font-semibold hover:text-gray-300">
          Jetzat omelde
        </a>
      </p>
      <button
        onClick={() => setIsVisible(false)}
        className="absolute right-4 top-1/2 -translate-y-1/2 hover:opacity-70 transition-opacity"
        aria-label="Close banner"
      >
        <X className="h-4 w-4" />
      </button>
    </div>
  );
}
