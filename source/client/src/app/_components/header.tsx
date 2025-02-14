"use client";
import { usePathname } from "next/navigation";

import FrontHeader from "./front-header";

export default function Header() {
  const pathname = usePathname();
  const isExcludedRoute =
    pathname?.startsWith("/admin") || pathname?.startsWith("/account");

  if (isExcludedRoute) {
    return;
  }

  return <FrontHeader />;
}
