import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
import Image from "next/image";

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
          <CarouselItem
            key={index}
            className="basis-1/2 md:basis-1/3 xl:basis-1/4"
          >
            <Card className="border-0 shadow-none">
              <CardContent className="p-0">
                <Image
                  src="https://placehold.co/400x300.jpg"
                  alt="Slider Image"
                  width={400}
                  height={300}
                  className="rounded-lg shadow"
                />
                <h4>General Mathematics</h4>
                <div>Math</div>
              </CardContent>
            </Card>
          </CarouselItem>
        ))}
      </CarouselContent>
      <CarouselPrevious className="hidden lg:flex -left-4 " />
      <CarouselNext className="hidden lg:flex -right-4 " />
    </Carousel>
  );
}
