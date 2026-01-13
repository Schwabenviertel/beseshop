import Link from "next/link";

export function Logo() {
  return (
    <Link href="/" className="flex items-center gap-2 hover:opacity-80 transition-opacity">
      <span className="text-3xl font-black tracking-tight uppercase">
        BESE.CO
      </span>
    </Link>
  );
}
