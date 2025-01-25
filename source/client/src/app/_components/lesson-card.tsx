import { Card, CardContent } from "@/components/ui/card";
import Image from "next/image";
import Link from "next/link";

interface CardProps {
  title: string;
  imageUrl: string;
  category: string;
}

export default function LessonCard(props: CardProps) {
  return (
    <Card className="lesson-card border overflow-hidden">
      <CardContent className="p-0">
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
            <h5 className="text-primary">{props.category}</h5>
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
