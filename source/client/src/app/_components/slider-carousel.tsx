"use client";

import { Card, CardContent } from "@/components/ui/card";
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
  CarouselApi,
} from "@/components/ui/carousel";
import Image from "next/image";
import { useEffect, useState } from "react";
import { Maximize } from "lucide-react";

export default function SliderCarousel() {
  const [mainApi, setMainApi] = useState<CarouselApi>();
  const [thumbnailApi, setThumbnailApi] = useState<CarouselApi>();
  const [current, setCurrent] = useState(0);
  const [isHover, setHover] = useState<boolean>(false);
  const [count, setCount] = useState(0);

  const handleSelect = (firstApi: CarouselApi, secondApi: CarouselApi) => {
    if (firstApi && secondApi) {
      const selected = firstApi.selectedScrollSnap();
      setCurrent(selected);
      secondApi.scrollTo(selected);
    }
  };

  const handleTopSelect = () => handleSelect(mainApi, thumbnailApi);
  const handleBottomSelect = () => handleSelect(thumbnailApi, mainApi);

  /**
   * Runs everytime mainApi and setMainApi changes
   * This effect can be used to perform side effects such as fetching data
   * or updating the state based on the values of `mainApi` and `setMainApi`.
   *
   * @function
   */
  useEffect(() => {
    if (!mainApi || !thumbnailApi) {
      return;
    }

    setCount(mainApi.scrollSnapList().length);
    mainApi.on("select", handleTopSelect);
    thumbnailApi.on("select", handleBottomSelect);

    return () => {
      mainApi.off("select", handleTopSelect);
      thumbnailApi.off("select", handleBottomSelect);
    };
  }, [mainApi, thumbnailApi]);

  const handleClick = (index: number) => {
    if (!mainApi || !thumbnailApi) {
      return;
    }
    thumbnailApi.scrollTo(index);
    mainApi.scrollTo(index);
    setCurrent(index);
  };

  return (
    <>
      <div id="slider-main-carousel">
        <Carousel
          setApi={setMainApi}
          className="relative cursor-pointer"
          onMouseEnter={() => setHover(true)}
          onMouseLeave={() => setHover(false)}
        >
          <div
            className={`absolute top-0 z-[1] m-4 px-5 py-2 text-sm rounded-md bg-black/50 text-white hover-hidden ${
              isHover ? "hover-show" : ""
            }`}
          >
            {current + 1}/{count}
          </div>

          <div
            className={`absolute top-0 right-0 z-[1] m-4 p-2 rounded-full bg-black/50 text-white hover-hidden ${
              isHover ? "hover-show" : ""
            }`}
          >
            <Maximize size="24" />
          </div>

          <CarouselContent>
            {Array.from({ length: 10 }).map((_, index) => (
              <CarouselItem key={index}>
                <Card className="border-0">
                  <CardContent className="p-0">
                    <Image
                      src="/assets/history-sample.webp"
                      width={1033}
                      height={580}
                      alt="Single Slide history image"
                      className="rounded-md"
                    />
                  </CardContent>
                </Card>
              </CarouselItem>
            ))}
          </CarouselContent>
          <CarouselPrevious className="hidden md:flex left-2 w-10 h-10 shadow border border-gray-200" />
          <CarouselNext className="hidden md:flex right-2 w-10 h-10 shadow border border-gray-200" />
        </Carousel>
      </div>
      <div id="slider-thumbnail" className="pt-5">
        <Carousel
          setApi={setThumbnailApi}
          className="cursor-pointer"
          onMouseEnter={() => setHover(true)}
          onMouseLeave={() => setHover(false)}
        >
          <CarouselContent>
            {Array.from({ length: 10 }).map((_, index) => (
              <CarouselItem
                key={index}
                className="basis-1/8"
                onClick={() => handleClick(index)}
              >
                <Card className="border-0">
                  <CardContent className="p-0">
                    <Image
                      className={`${
                        index === current
                          ? "border-2 border-primary"
                          : "border-0"
                      }`}
                      src="/assets/history-sample.webp"
                      width={123}
                      height={80}
                      alt="Single Slide history image"
                    />
                  </CardContent>
                </Card>
              </CarouselItem>
            ))}
          </CarouselContent>
          <CarouselPrevious className="hidden md:flex -left-4 w-8 h-8 shadow border border-gray-200" />
          <CarouselNext className="hidden md:flex -right-4 w-8 h-8 shadow border border-gray-200" />
        </Carousel>
      </div>
    </>
  );
}
