import React from "react";
import { Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import LessonCarousel from "./_components/lesson-carousel";
import { Card, CardContent } from "@/components/ui/card";
import Image from "next/image";
import Link from "next/link";

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
        <div id="home-page-sections" className="max-w-8xl mx-auto px-5">
          <div id="featured-section" className="md:pt-16 pt-8 md:pb-8 pb-4">
            <h2 className="font-semibold">Featured</h2>
            <div id="featured-slide">
              <LessonCarousel />
            </div>
          </div>
          <div
            id="recent-lesson-section"
            className="md:pt-16 pt-8 md:pb-8 pb-4"
          >
            <h2 className="font-semibold">Recent Lesson Slideshow</h2>
            <div
              id="recent-lesson-card"
              className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
            >
              {Array.from({ length: 6 }).map((_, index) => (
                <Card key={index} className="border">
                  <CardContent className="p-0">
                    <Image
                      src="https://placehold.co/500x300.jpg"
                      alt="Slider Image"
                      width={400}
                      height={300}
                      className="w-full"
                    />
                  </CardContent>
                  <div id="card-footer" className="p-5">
                    <div className="grid grid-cols-2 w-full">
                      <div id="recent-headers">
                        <h4>General Mathematics</h4>
                        <h5 className="text-primary">Math</h5>
                      </div>
                      <div id="slide-logos">
                        <div className="flex gap-2 justify-end">
                          <Link href="https://www.canva.com" target="_blank">
                            <Image
                              src="/assets/canva.svg"
                              alt="Canva Icon"
                              width={32}
                              height={32}
                              className="w-8 h-8"
                            />
                          </Link>
                          <Link
                            href="https://www.google.com/slides/about/"
                            target="_blank"
                          >
                            <Image
                              src="/assets/google-slide.svg"
                              alt="Google Slide Icon"
                              width={32}
                              height={32}
                              className="w-8 h-8"
                            />
                          </Link>
                          <Link
                            href="https://www.microsoft.com/en-us/microsoft-365/powerpoint"
                            target="_blank"
                          >
                            <Image
                              src="/assets/ppt.svg"
                              alt="PPT Icon"
                              width={32}
                              height={32}
                              className="w-8 h-8"
                            />
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                </Card>
              ))}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
