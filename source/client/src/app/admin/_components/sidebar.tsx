"use client";

import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar";

import {
  LayoutDashboard,
  Files,
  BookOpen,
  Image,
  ClipboardList,
  LucideIcon,
  Inbox,
  Search,
  Settings2,
  Users,
  X,
} from "lucide-react";

import { useSidebar } from "@/components/ui/sidebar";

type Items = {
  title: string;
  url: string;
  icon: LucideIcon;
};

const contentItems: Items[] = [
  {
    title: "Pages",
    url: "#",
    icon: Files,
  },
  {
    title: "Lessons",
    url: "#",
    icon: BookOpen,
  },
  {
    title: "Assets",
    url: "#",
    icon: Image,
  },
  {
    title: "Menus",
    url: "#",
    icon: ClipboardList,
  },
];

const toolItems: Items[] = [
  {
    title: "Forms",
    url: "#",
    icon: Inbox,
  },
  {
    title: "SEO",
    url: "#",
    icon: Search,
  },
  {
    title: "Utilities",
    url: "#",
    icon: Settings2,
  },
];

export default function AppSideBar() {
  const { isMobile } = useSidebar();
  return (
    <Sidebar>
      {isMobile == true ? (
        <SidebarHeader>
          <X />
        </SidebarHeader>
      ) : (
        ""
      )}

      <SidebarContent className={isMobile == true ? "pt-5" : "pt-20"}>
        <SidebarGroup>
          <SidebarGroupContent>
            <SidebarMenu>
              <SidebarMenuItem title="Dashboard">
                <SidebarMenuButton asChild className="text-base">
                  <a href="/admin">
                    <LayoutDashboard />
                    <span>Dashboard</span>
                  </a>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
        <SidebarGroup>
          <SidebarGroupLabel className="text-base text-primary tracking-wider">
            Content
          </SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {contentItems.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild className="text-base">
                    <a href={item.url}>
                      <item.icon />
                      <span>{item.title}</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
        <SidebarGroup>
          <SidebarGroupLabel className="text-base text-primary tracking-wider">
            Tools
          </SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              {toolItems.map((item) => (
                <SidebarMenuItem key={item.title}>
                  <SidebarMenuButton asChild className="text-base">
                    <a href={item.url}>
                      <item.icon />
                      <span>{item.title}</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              ))}
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
        <SidebarGroup>
          <SidebarGroupLabel className="text-base text-primary tracking-wider">
            Users
          </SidebarGroupLabel>
          <SidebarGroupContent>
            <SidebarMenu>
              <SidebarMenuItem title="Users">
                <SidebarMenuButton asChild className="text-base">
                  <a href="/admin">
                    <Users />
                    <span>Users</span>
                  </a>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarMenu>
          </SidebarGroupContent>
        </SidebarGroup>
      </SidebarContent>
    </Sidebar>
  );
}
