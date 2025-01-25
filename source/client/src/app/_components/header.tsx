"use client";
import { usePathname } from "next/navigation";

import FrontHeader from "./front-header";

export default function Header() {
  const pathname = usePathname();
  const isAdminRoute = pathname?.startsWith("/admin");

  if (isAdminRoute) {
    return;
  }

  return <FrontHeader />;
}
