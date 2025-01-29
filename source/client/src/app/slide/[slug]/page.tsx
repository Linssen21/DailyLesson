"use client";

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
      <div className="grid grid-cols-[2fr_1fr]">
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
          <h1 id="slide-title" className="text-[32px] py-5">
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
        className="py-7 grid grid-cols-[2fr_1fr] gap-7"
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
    </div>
  );
}
