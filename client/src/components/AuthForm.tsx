import { useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from "@/components/ui/form";
import { Eye, EyeOff, LogIn, UserPlus, Truck } from "lucide-react";
import { useAuth } from "@/hooks/useAuth";
import logoPath from "@/assets/logo.png";

const loginSchema = z.object({
  email: z.string().email("Неверный формат email"),
  password: z.string().min(6, "Пароль должен содержать минимум 6 символов"),
});

const registerSchema = z.object({
  email: z.string().email("Неверный формат email"),
  password: z.string().min(6, "Пароль должен содержать минимум 6 символов"),
  confirmPassword: z.string(),
  firstName: z.string().min(1, "Имя обязательно"),
  lastName: z.string().min(1, "Фамилия обязательна"),
  role: z.string().min(1, "Роль обязательна"),
}).refine((data) => data.password === data.confirmPassword, {
  message: "Пароли не совпадают",
  path: ["confirmPassword"],
});

const roleOptions = [
  { value: "прораб", label: "Прораб" },
  { value: "логист", label: "Логист" },
  { value: "руководитель", label: "Руководитель СМТ" },
  { value: "финансовый", label: "Финансовый директор" },
  { value: "генеральный", label: "Генеральный директор" },
];

export default function AuthForm() {
  const { loginMutation, registerMutation } = useAuth();
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);

  const loginForm = useForm({
    resolver: zodResolver(loginSchema),
    defaultValues: {
      email: "",
      password: "",
    },
  });

  const registerForm = useForm({
    resolver: zodResolver(registerSchema),
    defaultValues: {
      email: "",
      password: "",
      confirmPassword: "",
      firstName: "",
      lastName: "",
      role: "прораб",
    },
  });

  const onLogin = (data: z.infer<typeof loginSchema>) => {
    loginMutation.mutate(data);
  };

  const onRegister = (data: z.infer<typeof registerSchema>) => {
    const { confirmPassword, ...registerData } = data;
    registerMutation.mutate(registerData);
  };

  return (
    <div className="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
      {/* Background decoration */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-96 h-96 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full opacity-20 blur-3xl floating-animation"></div>
        <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full opacity-20 blur-3xl floating-animation" style={{animationDelay: '2s'}}></div>
        <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-gradient-to-br from-pink-400 to-orange-400 rounded-full opacity-10 blur-3xl floating-animation" style={{animationDelay: '4s'}}></div>
      </div>
      <div className="max-w-md w-full space-y-8">
        <div className="text-center relative z-10">
          <div className="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl pulse-glow p-2">
            <img src={logoPath} alt="Хром-KZ" className="h-16 w-16 object-contain" />
          </div>
          <h2 className="text-4xl font-bold gradient-text mb-4">
            Хром-KZ
          </h2>
          <p className="mt-2 text-lg text-gray-600">
            Система управления транспортом
          </p>
        </div>

        <Card className="glass-card border-none mt-8 relative z-10">
          <CardHeader>
            <CardTitle className="text-center text-gray-800 text-2xl">Авторизация</CardTitle>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="login" className="w-full">
              <TabsList className="grid w-full grid-cols-2 bg-purple-100/50 border-purple-200/50 p-1">
                <TabsTrigger value="login" className="text-gray-700 data-[state=active]:bg-gradient-to-r data-[state=active]:from-purple-500 data-[state=active]:to-pink-500 data-[state=active]:text-white data-[state=active]:shadow-lg">Вход</TabsTrigger>
                <TabsTrigger value="register" className="text-gray-700 data-[state=active]:bg-gradient-to-r data-[state=active]:from-purple-500 data-[state=active]:to-pink-500 data-[state=active]:text-white data-[state=active]:shadow-lg">Регистрация</TabsTrigger>
              </TabsList>

              <TabsContent value="login" className="space-y-4">
                <Form {...loginForm}>
                  <form onSubmit={loginForm.handleSubmit(onLogin)} className="space-y-4">
                    <FormField
                      control={loginForm.control}
                      name="email"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Email</FormLabel>
                          <FormControl>
                            <Input {...field} type="email" placeholder="your@email.com" className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300" />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    
                    <FormField
                      control={loginForm.control}
                      name="password"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Пароль</FormLabel>
                          <FormControl>
                            <div className="relative">
                              <Input 
                                {...field} 
                                type={showPassword ? "text" : "password"} 
                                placeholder="••••••••" 
                                className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300"
                              />
                              <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-purple-100/50 text-gray-600 hover:text-purple-600"
                                onClick={() => setShowPassword(!showPassword)}
                              >
                                {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                              </Button>
                            </div>
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />

                    <Button 
                      type="submit" 
                      className="w-full btn-gradient text-white font-semibold py-3 text-lg shadow-lg hover:shadow-xl transition-all duration-300" 
                      disabled={loginMutation.isPending}
                    >
                      {loginMutation.isPending ? (
                        <div className="flex items-center">
                          <div className="loading-spinner w-5 h-5 mr-2"></div>
                          Вход...
                        </div>
                      ) : (
                        <>
                          <LogIn className="w-5 h-5 mr-2" />
                          Войти
                        </>
                      )}
                    </Button>
                  </form>
                </Form>
              </TabsContent>

              <TabsContent value="register" className="space-y-4">
                <Form {...registerForm}>
                  <form onSubmit={registerForm.handleSubmit(onRegister)} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={registerForm.control}
                        name="firstName"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel className="text-gray-700 font-semibold">Имя</FormLabel>
                            <FormControl>
                              <Input {...field} placeholder="Имя" className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300" />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      
                      <FormField
                        control={registerForm.control}
                        name="lastName"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel className="text-gray-700 font-semibold">Фамилия</FormLabel>
                            <FormControl>
                              <Input {...field} placeholder="Фамилия" className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300" />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    </div>

                    <FormField
                      control={registerForm.control}
                      name="email"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Email</FormLabel>
                          <FormControl>
                            <Input {...field} type="email" placeholder="your@email.com" className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300" />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />

                    <FormField
                      control={registerForm.control}
                      name="role"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Роль</FormLabel>
                          <Select onValueChange={field.onChange} defaultValue={field.value}>
                            <FormControl>
                              <SelectTrigger className="bg-white/70 border-purple-200/50 text-gray-800 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300">
                                <SelectValue />
                              </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                              {roleOptions.map((option) => (
                                <SelectItem key={option.value} value={option.value}>
                                  {option.label}
                                </SelectItem>
                              ))}
                            </SelectContent>
                          </Select>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    
                    <FormField
                      control={registerForm.control}
                      name="password"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Пароль</FormLabel>
                          <FormControl>
                            <div className="relative">
                              <Input 
                                {...field} 
                                type={showPassword ? "text" : "password"} 
                                placeholder="••••••••" 
                                className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300"
                              />
                              <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-purple-100/50 text-gray-600 hover:text-purple-600"
                                onClick={() => setShowPassword(!showPassword)}
                              >
                                {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                              </Button>
                            </div>
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    
                    <FormField
                      control={registerForm.control}
                      name="confirmPassword"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel className="text-gray-700 font-semibold">Подтвердите пароль</FormLabel>
                          <FormControl>
                            <div className="relative">
                              <Input 
                                {...field} 
                                type={showConfirmPassword ? "text" : "password"} 
                                placeholder="••••••••" 
                                className="bg-white/70 border-purple-200/50 text-gray-800 placeholder-gray-400 focus:ring-purple-500 focus:border-purple-500 focus:ring-2 transition-all duration-300"
                              />
                              <Button
                                type="button"
                                variant="ghost"
                                size="sm"
                                className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-purple-100/50 text-gray-600 hover:text-purple-600"
                                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                              >
                                {showConfirmPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                              </Button>
                            </div>
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />

                    <Button 
                      type="submit" 
                      className="w-full btn-gradient text-white font-semibold py-3 text-lg shadow-lg hover:shadow-xl transition-all duration-300" 
                      disabled={registerMutation.isPending}
                    >
                      {registerMutation.isPending ? (
                        <div className="flex items-center">
                          <div className="loading-spinner w-5 h-5 mr-2"></div>
                          Регистрация...
                        </div>
                      ) : (
                        <>
                          <UserPlus className="w-5 h-5 mr-2" />
                          Зарегистрироваться
                        </>
                      )}
                    </Button>
                  </form>
                </Form>
              </TabsContent>
            </Tabs>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}