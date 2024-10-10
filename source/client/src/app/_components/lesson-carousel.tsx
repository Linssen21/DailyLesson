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
            <Card className="border-0">
              <CardContent className="p-0">
                <Image
                  src="https://placehold.co/400x300.jpg"
                  alt="Slider Image"
                  width={400}
                  height={300}
                  className="rounded-lg shadow"
                />
                <h4>General Mathematics</h4>
                <h5 className="text-primary">Math</h5>
              </CardContent>
            </Card>
          </CarouselItem>
        ))}
      </CarouselContent>
      <CarouselPrevious className="hidden md:flex -left-5 w-10 h-10 shadow border border-gray-200" />
      <CarouselNext className="hidden md:flex -right-5 w-10 h-10 shadow border border-gray-200" />
    </Carousel>
  );
}
