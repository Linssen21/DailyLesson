import React from "react";
import { Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import LessonCarousel from "./_components/lesson-carousel";

export default function Home() {
  return (
    <>
      <div id="home-page">
        <div
          id="home-hero"
          className="md:pt-32 pt-24 md:pb-24 pb-16 px-5 md:px-5 bg-sectionBackground"
        >
          <div
            id="hero-content"
            className="flex flex-col items-center text-center"
          >
            <h1 className="mb-5">Search engaging lesson’s, faster</h1>
            <h2 className="mb-10 text-[20px] md:text-[26px]">
              Free lesson presentation’s for any use
            </h2>
            <form className="mx-auto flex-1 sm:flex-initial md:w-[646px] w-full">
              <div className="relative">
                <Search className="absolute right-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                <Input
                  type="search"
                  placeholder="Search slides..."
                  className="pr-8 w-full"
                />
              </div>
            </form>
          </div>
        </div>
        <div id="home-page-sections" className="max-w-[1280px] mx-auto px-5">
          <div id="featured-section" className="md:py-16 py-8">
            <h2 className="font-semibold">Featured</h2>
            <div id="featured-slide">
              <LessonCarousel />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
