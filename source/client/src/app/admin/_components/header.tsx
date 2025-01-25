"use client";
import { Input } from "@/components/ui/input";
import {
  Search,
  Bookmark,
  Settings,
  BookOpen,
  Globe,
  Menu,
} from "lucide-react";
import { Avatar, AvatarImage, AvatarFallback } from "@/components/ui/avatar";
import { useSidebar } from "@/components/ui/sidebar";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Separator } from "@/components/ui/separator";
import Link from "next/link";

export default function AdminHeader() {
  const { toggleSidebar } = useSidebar();
  return (
    <header className="flex fixed top-0 left-0 right-0 z-30 h-16 items-center gap-4 border-b bg-background px-4 xl:px-12 lg:px-8 shadow">
      <Menu className="flex-shrink-0 cursor-pointer" onClick={toggleSidebar} />

      <div className="text-[#008dda] text-xl font-semibold">Lessons</div>
      <div className="flex w-full md:w-auto items-center gap-4 md:gap-2 lg:gap-4 m-auto">
        <form className="flex-1 sm:flex-initial hidden md:flex">
          <div className="relative">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              type="search"
              placeholder="Search..."
              className="border border-gray-400 pl-10 md:w-[400px] lg:w-[500px] xl:w-[650px]"
            />
          </div>
        </form>
      </div>
      <div className="lg:inline-flex gap-3 hidden">
        <Bookmark size={22} strokeWidth={1.5} />
        <Settings size={22} strokeWidth={1.5} />
        <BookOpen size={22} strokeWidth={1.5} />
        <Globe size={22} strokeWidth={1.5} />
      </div>
      <div className="ml-6">
        <Popover>
          <PopoverTrigger asChild>
            <Avatar className="cursor-pointer">
              <AvatarImage src="/assets/Bojji_2022_anime_ending.webp" />
              <AvatarFallback>BD</AvatarFallback>
            </Avatar>
          </PopoverTrigger>
          <PopoverContent align="end" className="w-64">
            <div className="grid gap-4">
              <div className="space-y-2">
                <div className="text-base leading-none">
                  test.admin001@example.com
                </div>
                <Separator />
                <div className="text-sm leading-none flex flex-col">
                  <Link className="pop-up-links" href="/profile">
                    Profile
                  </Link>
                  <Link className="pop-up-links" href="/preferences">
                    Preferences
                  </Link>
                  <Link className="pop-up-links" href="/logout">
                    Log out
                  </Link>
                </div>
              </div>
            </div>
          </PopoverContent>
        </Popover>
      </div>
    </header>
  );
}
