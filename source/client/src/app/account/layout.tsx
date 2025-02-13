export default function AuthLayout({
  children,
}: Readonly<{ children: React.ReactNode }>) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_2fr]">
      {children}
    </div>
  );
}
