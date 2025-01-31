"use client";
import Image from "next/image";
import Link from "next/link";
import { usePathname } from "next/navigation";

export default function Footer() {
  const pathname = usePathname();
  const isAdminRoute = pathname?.startsWith("/admin");

  if (isAdminRoute) return;
  return (
    <footer className="bg-black text-white">
      <div className="max-w-8xl mx-auto px-5 md:py-20 py-14">
        <div className="grid lg:grid-cols-6 sm:grid-cols-4 grid-cols-1 lg:gap-5 gap-4 ">
          <div className="lg:col-span-3 col-span-2">
            <Link
              href="/"
              id="logo-container"
              className="flex items-center gap-2 text-lg font-semibold md:text-base"
            >
              <Image
                src="/assets/footer-logo.svg"
                alt="Site Logo"
                width={180}
                height={30}
                className="cursor-pointer flex-shrink-0"
              />
            </Link>
          </div>

          <div className="lg:col-span-1 col-span-2">
            <h4>Content</h4>
            <ul>
              <li>All</li>
              <li>Recent</li>
              <li>Popular</li>
              <li>Google Slides</li>
              <li>PowerPoint</li>
              <li>Canva</li>
            </ul>
          </div>

          <div className="lg:col-span-1 col-span-2 lg:pt-0 pt-5">
            <h4>Support</h4>
            <ul>
              <li>About</li>
              <li>FAQ</li>
              <li>Privacy Policy</li>
              <li>Terms and conditions</li>
              <li>Cookie Policy</li>
              <li>Contact</li>
            </ul>
          </div>

          <div className="lg:col-span-1 col-span-2 lg:pt-0 pt-5">
            <h4>Social Media</h4>
            <ul>
              <li className="flex gap-[10px]">
                <Image
                  src="/assets/mdi_instagram.svg"
                  width={24}
                  height={24}
                  alt="instagram icon"
                />
                <span>Instagram</span>
              </li>
              <li className="flex gap-[10px]">
                <Image
                  src="/assets/pajamas_twitter.svg"
                  width={24}
                  height={24}
                  alt="twitter icon"
                />
                <span>Twitter</span>
              </li>
              <li className="flex gap-[10px]">
                <Image
                  src="/assets/ic_baseline-facebook.svg"
                  width={24}
                  height={24}
                  alt="facebook icon"
                />
                <span>Facebook</span>
              </li>
              <li className="flex gap-[10px]">
                <Image
                  src="/assets/mdi_linkedin.svg"
                  width={24}
                  height={24}
                  alt="linkedin icon"
                />
                <span>LinkedIn</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div className="max-w-8xl mx-auto py-9 text-center border-t border-t-white">
        © 2024 Lessons. All rights reserved.
      </div>
    </footer>
  );
}
