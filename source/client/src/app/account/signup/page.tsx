"use client";

import AuthButton from "@/app/_components/auth-button";
import { Button } from "@/components/ui/button";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { ChevronLeft, EyeIcon, EyeOffIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import { zodResolver } from "@hookform/resolvers/zod";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { Input } from "@/components/ui/input";
import { signUpFormSchema } from "@/app/_user-cases/schemas";

export default function SignUp() {
  const [isSignUpEmail, setIsSignUpEmail] = useState<boolean>(false);
  const [showPassword, setShowPassword] = useState<boolean>(false);
  const [showConfPass, setShowConfPass] = useState<boolean>(false);
  const form = useForm<z.infer<typeof signUpFormSchema>>({
    resolver: zodResolver(signUpFormSchema),
    defaultValues: {
      email: "",
      password: "",
      confirmPassword: "",
    },
  });

  function onSubmit(values: z.infer<typeof signUpFormSchema>) {
    console.log(values);
  }

  return (
    <div className="flex flex-col items-center justify-start py-[100px] relative">
      <div className="absolute top-0 w-full p-4">
        <Button
          onClick={() => setIsSignUpEmail(false)}
          variant="ghost"
          className={`p-0 hover:bg-transparent text-primary hover:text-primary ${
            !isSignUpEmail ? "hidden" : ""
          }`}
        >
          <ChevronLeft className="mr-2" />
          Back
        </Button>
      </div>
      <div className="w-[300px] mx-auto">
        <Link href="/" className="flex justify-center">
          <Image
            src="/assets/logo.svg"
            alt="Site Logo"
            width={163}
            height={33}
            className="cursor-pointer flex-shrink-0"
          />
        </Link>
        <h3 className="font-medium pt-[70px] pb-[45px] text-center">
          Create an account
        </h3>
        <div className={`flex flex-col gap-5 ${isSignUpEmail ? "hidden" : ""}`}>
          <AuthButton
            title="Continue with Google"
            src="/assets/google.svg"
            alt="Google button"
          />
          <AuthButton
            title="Continue with Facebook"
            src="/assets/facebook.svg"
            alt="Facebook button"
          />
          <AuthButton
            title="Continue with Email"
            src="/assets/email.svg"
            alt="Email button"
            onClick={() => setIsSignUpEmail(true)}
          />
        </div>
        <div className={`${!isSignUpEmail ? "hidden" : ""}`}>
          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="grid gap-3">
              <FormField
                control={form.control}
                name="email"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                      <Input type="text" {...field} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="password"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Password</FormLabel>
                    <FormControl>
                      <div className="relative">
                        <Input
                          type={showPassword ? "text" : "password"}
                          {...field}
                        />
                        <Button
                          type="button"
                          variant="ghost"
                          size="sm"
                          className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                          onClick={() => setShowPassword((prev) => !prev)}
                        >
                          {showPassword ? (
                            <EyeIcon className="h-4 w-4" aria-hidden="true" />
                          ) : (
                            <EyeOffIcon
                              className="h-4 w-4"
                              aria-hidden="true"
                            />
                          )}
                        </Button>
                      </div>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="confirmPassword"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Confirm Password</FormLabel>
                    <FormControl>
                      <div className="relative">
                        <Input
                          type={showConfPass ? "text" : "password"}
                          {...field}
                        />
                        <Button
                          type="button"
                          variant="ghost"
                          size="sm"
                          className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                          onClick={() => setShowConfPass((prev) => !prev)}
                        >
                          {showConfPass ? (
                            <EyeIcon className="h-4 w-4" aria-hidden="true" />
                          ) : (
                            <EyeOffIcon
                              className="h-4 w-4"
                              aria-hidden="true"
                            />
                          )}
                        </Button>
                      </div>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <Button type="submit" className="mt-5 text-base font-medium">
                Sign up
              </Button>
            </form>
          </Form>
        </div>
        <div className=" h-px bg-gray-300 mt-12 mb-10"></div>
        <div className="text-[14px] font-medium text-center">
          Already have an account?{" "}
          <Link className="text-primary" href="/account/login">
            Login
          </Link>
        </div>
      </div>
    </div>
  );
}
