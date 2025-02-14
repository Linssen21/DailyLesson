import { z } from "zod";

// Setup the password validation
const passwordSchema = z
  .string()
  .min(8, { message: "Password must be at least 8 characters." })
  .max(20, { message: "Password must not be greater than 20 characters." })
  .refine((password) => /[A-Z]/.test(password), {
    message: "Password must contain at least one uppercase letter.",
  })
  .refine((password) => /[a-z]/.test(password), {
    message: "Password must contain at least one lowercase letter.",
  })
  .refine((password) => /[0-9]/.test(password), {
    message: "Password must contain at least one number.",
  })
  .refine((password) => /[!@#$%^&*]/.test(password), {
    message: "Password must contain at least one special character (!@#$%^&*).",
  });

// Setup the shape/schema and validation of the Sign Up form
export const logInFormSchema = z.object({
  email: z
    .string()
    .email({ message: "Invalid email address" })
    .min(10, { message: "Email must be at least 10 characters." }),
  password: passwordSchema,
  stayLogin: z.boolean().optional(),
});

// Setup the shape/schema and validation of the Sign Up form
export const signUpFormSchema = z
  .object({
    email: z
      .string()
      .email({ message: "Invalid email address" })
      .min(10, { message: "Email must be at least 10 characters." }),
    password: passwordSchema,
    confirmPassword: z.string(),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: "Passwords do not match.",
    path: ["confirmPassword"],
  });
