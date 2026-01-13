"use client";

import { useRouter } from "next/navigation";
import { LogOut } from "lucide-react";
import { Button } from "@/components/ui/button";

export function LogoutButton() {
  const router = useRouter();

  const handleLogout = async () => {
    try {
      await fetch("/api/auth/logout", {
        method: "POST",
      });
      router.push("/");
      router.refresh();
    } catch (error) {
      console.error("Logout failed:", error);
    }
  };

  return (
    <Button
      variant="ghost"
      size="lg"
      onClick={handleLogout}
      className="gap-2 hover:bg-destructive/10 hover:text-destructive transition-colors"
    >
      <LogOut className="w-5 h-5" />
      <span className="hidden sm:inline">Logout</span>
    </Button>
  );
}
