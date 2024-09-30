import Image from "next/image";

export default function Home() {
  return (
    <div>
      <Image src="/assets/logo.png" alt="Site Logo" width={100} height={100} />
    </div>
  );
}
