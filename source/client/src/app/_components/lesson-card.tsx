"use client";

import { Card, CardContent } from "@/components/ui/card";
import { Download, Heart } from "lucide-react";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";

interface CardProps {
  title: string;
  imageUrl: string;
  category: string;
}

export default function LessonCard(props: CardProps) {
  const [isHover, setHover] = useState<boolean>(false);
  return (
    <Card
      className="lesson-card border overflow-hidden"
      onMouseEnter={() => setHover(true)}
      onMouseLeave={() => setHover(false)}
    >
      <CardContent className="p-0 relative">
        <div
          className={`absolute right-0 m-2.5 hover-hidden ${
            isHover ? "hover-show" : ""
          }`}
        >
          <div className="inline-flex gap-2">
            <div className="p-1.5 bg-white rounded-sm shadow cursor-pointer">
              <Heart width={22} height={22} />
            </div>
            <div className="p-1.5 bg-white rounded-sm shadow cursor-pointer">
              <Download width={22} height={22} />
            </div>
          </div>
        </div>
        <Image
          src={props.imageUrl}
          alt="Slider Image"
          width={400}
          height={300}
          className="w-full"
        />
      </CardContent>
      <div id="card-footer" className="p-5">
        <div className="grid grid-cols-2 w-full">
          <div id="recent-headers">
            <h4>{props.title}</h4>
            <h5 className="text-primary pt-1">{props.category}</h5>
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
              <Link href="https://www.google.com/slides/about/" target="_blank">
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
  );
}
