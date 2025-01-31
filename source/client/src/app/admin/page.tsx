"use client";
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination";
import { TableBody, TableCell, TableRow, Table } from "@/components/ui/table";
import { CartesianGrid, Line, LineChart, XAxis, YAxis } from "recharts";
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart";
import {
  MonitorCheck,
  Inbox,
  TrendingUp,
  ChartNoAxesCombined,
} from "lucide-react";

type Month =
  | "January"
  | "February"
  | "March"
  | "April"
  | "May"
  | "June"
  | "July"
  | "August"
  | "September"
  | "October"
  | "November"
  | "December";
interface ChartData {
  month: Month;
  activity: number;
  formSubmission: number;
}

const chartData: ChartData[] = [
  { month: "January", activity: 186, formSubmission: 100 },
  { month: "February", activity: 256, formSubmission: 120 },
  { month: "March", activity: 237, formSubmission: 70 },
  { month: "April", activity: 120, formSubmission: 40 },
  { month: "May", activity: 209, formSubmission: 150 },
  { month: "June", activity: 214, formSubmission: 170 },
];

const chartConfig = {
  activity: {
    label: "Activity",
    color: "hsl(var(--chart-1))",
  },
  formSubmission: {
    label: "Form Submission",
    color: "hsl(var(--chart-2))",
  },
} satisfies ChartConfig;

export default function Admin() {
  return (
    <div className="max-w-[950px] m-auto grid gap-5">
      <div className="bg-white p-3 rounded-md shadow">
        👋 Welcome to the Lessons site admin dashboard!!
      </div>
      <div className="grid lg:grid-cols-2 gap-5">
        <div className="bg-white p-3 rounded-md shadow">
          <div className="p-2">
            <div className="flex gap-2 pb-3">
              <MonitorCheck />
              <h4>Activity</h4>
            </div>
            <span className="text-sm text-gray-400">Recently Published</span>
          </div>
          <div className="overflow-hidden">
            <Table>
              <TableBody>
                {Array.from({ length: 5 }).map((_, index) => (
                  <TableRow key={index}>
                    <TableCell className="inline-flex items-center gap-2">
                      <div className="w-[5px] h-[5px] bg-[#16A34A] rounded-full"></div>
                      Apr 10th, 7:31 am
                    </TableCell>
                    <TableCell className="max-w-[15ch] sm:max-w-[25ch] overflow-hidden text-ellipsis whitespace-nowrap">
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Donec vitae mollis eros. Aliquam non sollicitudin purus.
                      Vestibulum accumsan efficitur diam, quis mattis est congue
                      vel.
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
          <Pagination className="py-3 hidden md:block">
            <PaginationContent>
              <PaginationItem>
                <PaginationPrevious href="#" />
              </PaginationItem>
              {Array.from({ length: 5 }).map((_, index) => (
                <PaginationItem key={index}>
                  <PaginationLink href="#">{index + 1}</PaginationLink>
                </PaginationItem>
              ))}
              <PaginationItem>
                <PaginationEllipsis />
              </PaginationItem>
              <PaginationItem>
                <PaginationNext href="#" />
              </PaginationItem>
            </PaginationContent>
          </Pagination>
        </div>
        <div className="bg-white p-3 rounded-md shadow">
          <div className="p-2">
            <div className="flex gap-2 pb-3">
              <Inbox />
              <h4>Form Submission</h4>
            </div>
          </div>
          <div className="overflow-hidden">
            <Table>
              <TableBody>
                {Array.from({ length: 5 }).map((_, index) => (
                  <TableRow key={index}>
                    <TableCell>Apr 10th, 7:31 am</TableCell>
                    <TableCell>John Doe</TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
          <Pagination className="py-3 hidden md:block">
            <PaginationContent>
              <PaginationItem>
                <PaginationPrevious href="#" />
              </PaginationItem>
              {Array.from({ length: 3 }).map((_, index) => (
                <PaginationItem key={index}>
                  <PaginationLink href="#">{index + 1}</PaginationLink>
                </PaginationItem>
              ))}
              <PaginationItem>
                <PaginationEllipsis />
              </PaginationItem>
              <PaginationItem>
                <PaginationNext href="#" />
              </PaginationItem>
            </PaginationContent>
          </Pagination>
        </div>
      </div>
      <div className="bg-white p-3 rounded-md shadow">
        <div className="p-2">
          <div className="flex gap-2 pb-3">
            <ChartNoAxesCombined />
            <h4>Post Analytics</h4>
          </div>
        </div>
        <Card>
          <CardContent>
            <ChartContainer config={chartConfig}>
              <LineChart
                accessibilityLayer
                data={chartData}
                margin={{
                  left: 12,
                  right: 12,
                }}
              >
                <CartesianGrid vertical={false} />
                <XAxis
                  dataKey="month"
                  tickLine={false}
                  axisLine={false}
                  tickMargin={8}
                  tickFormatter={(value) => value.slice(0, 3)}
                />
                <YAxis width={10} />
                <ChartTooltip
                  cursor={false}
                  content={
                    <ChartTooltipContent
                      hideLabel
                      indicator="dot"
                      className="text-sm"
                    />
                  }
                />
                <Line
                  dataKey="activity"
                  type="linear"
                  stroke="var(--color-activity)"
                  strokeWidth={2}
                  dot={false}
                />
                <Line
                  dataKey="formSubmission"
                  type="linear"
                  stroke="var(--color-formSubmission)"
                  strokeWidth={2}
                  dot={false}
                />
              </LineChart>
            </ChartContainer>
          </CardContent>
          <CardFooter className="flex-col items-start gap-2 text-sm">
            <div className="flex gap-2 font-medium leading-none">
              Trending up by 5.2% this month <TrendingUp className="h-4 w-4" />
            </div>
            <div className="leading-none text-muted-foreground">
              Showing total activity and form submission for the last 6 months
            </div>
          </CardFooter>
        </Card>
      </div>
    </div>
  );
}
