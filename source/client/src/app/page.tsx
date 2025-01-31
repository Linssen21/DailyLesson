import React from "react";
import { Search } from "lucide-react";
import { Input } from "@/components/ui/input";
import LessonCarousel from "./_components/lesson-carousel";
import LessonCard from "./_components/lesson-card";
import { Button } from "@/components/ui/button";

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
            className="md:pt-16 pt-8 md:pb-8 pb-4 w-full"
          >
            <h2 className="font-semibold">Recent Lesson Slideshow</h2>
            <div
              id="recent-lesson-card"
              className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
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

          <div
            id="recent-lesson-section"
            className="md:pt-16 pt-8 md:pb-8 pb-4 w-full"
          >
            <h2 className="font-semibold">Science</h2>
            <div
              id="recent-lesson-card"
              className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
            >
              {Array.from({ length: 3 }).map((_, index) => (
                <LessonCard
                  key={index}
                  title="DNA Lesson"
                  category="Science"
                  imageUrl="https://placehold.co/500x300.jpg"
                />
              ))}
            </div>
            <div className="flex justify-center mt-11">
              <Button>See More</Button>
            </div>
          </div>

          <div
            id="recent-lesson-section"
            className="md:pt-16 pt-8 md:pb-8 pb-4 w-full"
          >
            <h2 className="font-semibold">English</h2>
            <div
              id="recent-lesson-card"
              className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
            >
              {Array.from({ length: 3 }).map((_, index) => (
                <LessonCard
                  key={index}
                  title="DNA Lesson"
                  category="English"
                  imageUrl="https://placehold.co/500x300.jpg"
                />
              ))}
            </div>
            <div className="flex justify-center mt-11">
              <Button>See More</Button>
            </div>
          </div>

          <div id="tags-section" className="md:py-20 py-16 w-full">
            <h2 className="font-semibold">
              Find free lesson’s that suit your needs
            </h2>
            <div className="grid md:grid-cols-2 gap-4">
              <div id="topic">
                <h3>Lesson’s by topic</h3>
                <ul className="flex gap-x-2 gap-y-3 flex-wrap justify-start">
                  {[
                    "Filipino",
                    "English",
                    "Mathematics",
                    "Science",
                    "Social Studies",
                    "Character Education",
                    "Music",
                    "Arts",
                    "Physical Education",
                    "Health",
                  ].map((language, index) => (
                    <li
                      key={index}
                      className="px-5 py-2 rounded bg-[#FCF7F1] text-sm font-medium"
                    >
                      {language}
                    </li>
                  ))}
                </ul>
              </div>
              <div id="grade-level">
                <h3>Lesson’s by Grade level</h3>
                <ul className="flex gap-x-2 gap-y-3 flex-wrap justify-start">
                  {[
                    "Kindergarten",
                    "Elementary School",
                    "Junior High School",
                    "Senior High School",
                    "College",
                  ].map((language, index) => (
                    <li
                      key={index}
                      className="px-5 py-2 rounded bg-[#FCF7F1] text-sm font-medium"
                    >
                      {language}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
