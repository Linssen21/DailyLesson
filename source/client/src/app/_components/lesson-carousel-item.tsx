import { Card, CardContent } from "@/components/ui/card";
import { CarouselItem } from "@/components/ui/carousel";
import Image from "next/image";

export default function LessonCarouselItem() {
  return (
    <CarouselItem className="lesson-carousel basis-1/2 md:basis-1/3 xl:basis-1/4">
      <Card className="border-0">
        <CardContent className="p-0">
          <Image
            src="https://placehold.co/400x300.jpg"
            alt="Slider Image"
            width={400}
            height={300}
            className="rounded-lg shadow"
          />
          <h4 className="pt-[15px]">General Mathematics</h4>
          <h5 className="text-primary">Math</h5>
        </CardContent>
      </Card>
    </CarouselItem>
  );
}
