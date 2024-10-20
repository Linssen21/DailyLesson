import {
  Carousel,
  CarouselContent,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";

import LessonCarouselItem from "./lesson-carousel-item";

export default function LessonCarousel() {
  return (
    <Carousel
      opts={{
        align: "start",
      }}
      className="w-full"
    >
      <CarouselContent>
        {Array.from({ length: 10 }).map((_, index) => (
          <LessonCarouselItem key={index} />
        ))}
      </CarouselContent>
      <CarouselPrevious className="hidden md:flex -left-5 w-10 h-10 shadow border border-gray-200" />
      <CarouselNext className="hidden md:flex -right-5 w-10 h-10 shadow border border-gray-200" />
    </Carousel>
  );
}
