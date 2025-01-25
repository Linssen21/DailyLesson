import { SidebarProvider } from "@/components/ui/sidebar";
import AppSideBar from "./_components/sidebar";
import AdminHeader from "./_components/header";
import { TooltipProvider } from "@/components/ui/tooltip";

export default function AdminLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <SidebarProvider>
      <TooltipProvider>
        <AppSideBar />
      </TooltipProvider>
      <AdminHeader />
      <main className="bg-gray-200 w-full py-20 lg:pt-28 px-5">{children}</main>
    </SidebarProvider>
  );
}
