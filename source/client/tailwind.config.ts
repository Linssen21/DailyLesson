import type { Config } from "tailwindcss";
import tailwindcssAnimate from "tailwindcss-animate";

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
        "h1-sm": [
          "32px",
          {
            lineHeight: "1.2",
          },
        ],
        "h1-md": [
          "40px",
          {
            lineHeight: "1.2",
          },
        ],
        "h1-lg": [
          "48px",
          {
            lineHeight: "1.2",
          },
        ],
        "h1-xl": [
          "56px",
          {
            lineHeight: "1.2",
          },
        ],
        "h2-sm": [
          "24px",
          {
            lineHeight: "1.2",
          },
        ],
        "h2-md": [
          "28px",
          {
            lineHeight: "1.2",
          },
        ],
        "h2-lg": [
          "32px",
          {
            lineHeight: "1.2",
          },
        ],
        "h2-xl": [
          "36px",
          {
            lineHeight: "1.2",
          },
        ],
        "h3-sm": [
          "20px",
          {
            lineHeight: "1.2",
          },
        ],
        "h3-md": [
          "22px",
          {
            lineHeight: "1.2",
          },
        ],
        "h3-lg": [
          "24px",
          {
            lineHeight: "1.2",
          },
        ],
        "h3-xl": [
          "24px",
          {
            lineHeight: "1.2",
          },
        ],
        "h4-sm": [
          "16px",
          {
            lineHeight: "1.2",
          },
        ],
        "h4-md": [
          "16px",
          {
            lineHeight: "1.2",
          },
        ],
        "h4-lg": [
          "18px",
          {
            lineHeight: "1.2",
          },
        ],
        "h4-xl": [
          "20px",
          {
            lineHeight: "1.2",
          },
        ],
        "h5-sm": [
          "14px",
          {
            lineHeight: "1.2",
          },
        ],
        "h5-md": [
          "16px",
          {
            lineHeight: "1.2",
          },
        ],
        "h5-lg": [
          "18px",
          {
            lineHeight: "1.2",
          },
        ],
        "h5-xl": [
          "20px",
          {
            lineHeight: "1.2",
          },
        ],
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
        sidebar: {
          DEFAULT: "hsl(var(--sidebar-background))",
          foreground: "hsl(var(--sidebar-foreground))",
          primary: "hsl(var(--sidebar-primary))",
          "primary-foreground": "hsl(var(--sidebar-primary-foreground))",
          accent: "hsl(var(--sidebar-accent))",
          "accent-foreground": "hsl(var(--sidebar-accent-foreground))",
          border: "hsl(var(--sidebar-border))",
          ring: "hsl(var(--sidebar-ring))",
        },
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
      boxShadow: {
        default: "0px 2px 4px -1px rgba(0, 0, 0, 0.06)",
      },
      maxWidth: {
        "8xl": "90rem",
      },
    },
  },
  plugins: [tailwindcssAnimate],
};
export default config;
