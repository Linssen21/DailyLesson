import Image, { ImageProps } from "next/image";
import Link from "next/link";

interface AuthButtonProps extends Omit<ImageProps, "src" | "alt"> {
  title: string;
  src: string;
  alt: string;
  href?: string;
  onClick?: () => void;
}

export default function AuthButton({
  title,
  src,
  alt,
  href,
  onClick,
  ...imageProps
}: AuthButtonProps) {
  const ButtonContent = (
    <button
      onClick={onClick}
      className="flex gap-4 items-center justify-center w-[300px] py-4 rounded border border-gray-400 shadow-md cursor-pointer hover:shadow-lg transition cursor-pointer"
    >
      <Image
        src={src}
        alt={alt}
        width={30}
        height={30}
        className="flex-shrink-0"
        {...imageProps}
      />
      <div className="font-medium">{title}</div>
    </button>
  );
  return href ? <Link href={href}>{ButtonContent}</Link> : ButtonContent;
}
