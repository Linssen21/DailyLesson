import Image from "next/image";
import Link from "next/link";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Search } from "lucide-react";

export default function Header() {
  return (
    <header className="flex sticky top-0 left-0 right-0 z-30 h-16 items-center gap-4 border-b bg-background px-4 md:px-6">
      <nav className="flex-col gap-6 text-lg font-medium md:flex md:flex-row md:items-center md:gap-5 md:text-sm md:flex-nowrap lg:gap-6">
        <Link
          href="/"
          id="logo-container"
          className="flex items-center gap-2 text-lg font-semibold md:text-base"
        >
          <Image
            src="/assets/logo.svg"
            alt="Site Logo"
            width={163}
            height={33}
            className="cursor-pointer flex-shrink-0"
          />
        </Link>
        <div id="menu-container" className="hidden md:flex justify-between">
          <div className="flex items-center">
            <div className="flex items-center group p-3 py-3 cursor-pointer">
              <span className="text-sm font-semibold">Create</span>
              <div className="ml-2">
                <Image src="/assets/arrow.svg" width={9} height={9} alt="" />
              </div>
            </div>
            <div className="flex items-center group p-3 py-3 cursor-pointer">
              <span className="text-sm font-semibold">Explore</span>
              <div className="ml-2">
                <Image src="/assets/arrow.svg" width={9} height={9} alt="" />
              </div>
            </div>
            <div className="flex items-center group p-3 py-3 cursor-pointer">
              <span className="text-sm font-semibold">Learn</span>
              <div className="ml-2">
                <Image src="/assets/arrow.svg" width={9} height={9} alt="" />
              </div>
            </div>
          </div>
        </div>
      </nav>
      <div className="flex w-full md:w-auto items-center gap-4 md:ml-auto md:gap-2 lg:gap-4 justify-end">
        <form className="ml-auto flex-1 sm:flex-initial hidden md:flex">
          <div className="relative">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              type="search"
              placeholder="Search slides..."
              className="border-0 bg-searchBackground pl-8 sm:w-[300px] md:w-[250px] lg:w-[500px]"
            />
          </div>
        </form>
        <div id="profile-container">
          <Button asChild>
            <Link href="/login">Log In</Link>
          </Button>
        </div>
      </div>
    </header>
  );
}
