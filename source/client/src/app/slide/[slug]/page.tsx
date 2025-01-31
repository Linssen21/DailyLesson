"use client";

import LessonCard from "@/app/_components/lesson-card";
import SliderCarousel from "@/app/_components/slider-carousel";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Button } from "@/components/ui/button";
import {
  BookOpen,
  DownloadIcon,
  Heart,
  Share2,
  MessageSquareMoreIcon,
} from "lucide-react";

export default function Slide() {
  return (
    <div className="max-w-8xl m-auto py-14 px-5">
      <div className="grid grid-cols-1 md:grid-cols-[2fr_1fr]">
        <div>
          <Breadcrumb>
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink href="/">Home</BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator>/</BreadcrumbSeparator>
              <BreadcrumbItem>
                <BreadcrumbLink href="/history">History</BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator>/</BreadcrumbSeparator>
              <BreadcrumbItem>
                <BreadcrumbPage>History of Philippines</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
          <h1 id="slide-title" className="md:text-[32px] py-5">
            History of Philippines
          </h1>
          <div className="px-5 py-2 inline-block w-auto shadow-md border border-black rounded cursor-pointer">
            <div className="flex gap-2">
              <BookOpen />
              <div className="text-base font-semibold">History</div>
            </div>
          </div>
        </div>
      </div>

      <div
        id="single-content-carousel"
        className="py-7 grid grid-cols-1 md:grid-cols-[2fr_1fr] gap-7"
      >
        <div>
          <SliderCarousel />
        </div>
        <div>
          <Button className="flex gap-2 w-full">
            <DownloadIcon className="h-6 w-6" />
            <span>Download this Lesson</span>
          </Button>
        </div>
      </div>

      <div id="single-content-share" className="flex gap-2.5">
        <div className="px-5 py-2 inline-block w-auto shadow-md border border-black rounded cursor-pointer">
          <div className="flex gap-2">
            <Heart />
            <div className="text-base font-semibold">Like</div>
          </div>
        </div>
        <div className="px-5 py-2 inline-block w-auto shadow-md border border-black rounded cursor-pointer">
          <div className="flex gap-2">
            <Share2 />
            <div className="text-base font-semibold">Share</div>
          </div>
        </div>
        <div className="px-5 py-2 inline-block w-auto shadow-md border border-black rounded cursor-pointer">
          <div className="flex gap-2">
            <MessageSquareMoreIcon />
            <div className="text-base font-semibold">Feedback</div>
          </div>
        </div>
      </div>

      <div id="single-content-desc" className="py-9 max-w-3xl">
        <h3 className="pb-1">History of Philippines Lesson</h3>
        <h5 className="text-gray-500">Free lesson presentation</h5>
        <p className="mt-5">
          Embark on a journey through time as we unravel the rich tapestry of
          the Philippines storied past. In this immersive lesson presentation,
          we delve deep into the annals of history to uncover the captivating
          narrative of this archipelago nation.
        </p>
      </div>

      <div id="single-tags">
        <h5 className="pb-3">Tags</h5>
        <ul className="flex gap-x-2 gap-y-3 flex-wrap justify-start">
          {["Filipino", "History", "Social Studies"].map((language, index) => (
            <li
              key={index}
              className="px-5 py-2 rounded bg-[#FCF7F1] text-sm font-medium"
            >
              {language}
            </li>
          ))}
        </ul>
      </div>

      <div id="single-related-post" className="py-10">
        <h5 className="pb-3">Related Lessons</h5>
        <div
          id="single-recent"
          className="pt-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7"
        >
          {Array.from({ length: 6 }).map((_, index) => (
            <LessonCard
              key={index}
              title="General Mathematics"
              category="Math"
              imageUrl="https://placehold.co/500x300.jpg"
            />
          ))}
        </div>
        <div className="flex justify-center mt-11">
          <Button>See More</Button>
        </div>
      </div>
    </div>
  );
}
