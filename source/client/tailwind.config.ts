import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      fontSize: {
        "h1-sm": ["32px", { lineHeight: "1.2" }], // Font size for smaller screens
        "h1-md": ["40px", { lineHeight: "1.2" }], // For medium screens
        "h1-lg": ["48px", { lineHeight: "1.2" }], // For large screens
        "h1-xl": ["56px", { lineHeight: "1.2" }], // For extra large screens

        // Custom h2 font sizes
        "h2-sm": ["24px", { lineHeight: "1.2" }], // Font size for smaller screens
        "h2-md": ["28px", { lineHeight: "1.2" }], // For medium screens
        "h2-lg": ["32px", { lineHeight: "1.2" }], // For large screens
        "h2-xl": ["36px", { lineHeight: "1.2" }], // For extra large screens
      },
      colors: {
        background: "hsl(var(--background))",
        searchBackground: "var(--search-background)",
        foreground: "hsl(var(--foreground))",
        default: "var(--primary-color)",
        sectionBackground: "var(--secondary-color)",
        secondary: {
          DEFAULT: "hsl(var(--secondary))",
          foreground: "hsl(var(--secondary-foreground))",
        },
        card: {
          DEFAULT: "hsl(var(--card))",
          foreground: "hsl(var(--card-foreground))",
        },
        popover: {
          DEFAULT: "hsl(var(--popover))",
          foreground: "hsl(var(--popover-foreground))",
        },
        primary: {
          DEFAULT: "hsl(var(--primary))",
          foreground: "hsl(var(--primary-foreground))",
        },
        muted: {
          DEFAULT: "hsl(var(--muted))",
          foreground: "hsl(var(--muted-foreground))",
        },
        accent: {
          DEFAULT: "hsl(var(--accent))",
          foreground: "hsl(var(--accent-foreground))",
        },
        destructive: {
          DEFAULT: "hsl(var(--destructive))",
          foreground: "hsl(var(--destructive-foreground))",
        },
        border: "hsl(var(--border))",
        input: "hsl(var(--input))",
        ring: "hsl(var(--ring))",
        chart: {
          "1": "hsl(var(--chart-1))",
          "2": "hsl(var(--chart-2))",
          "3": "hsl(var(--chart-3))",
          "4": "hsl(var(--chart-4))",
          "5": "hsl(var(--chart-5))",
        },
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
};
export default config;
