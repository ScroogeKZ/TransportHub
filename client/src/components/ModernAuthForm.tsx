import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/hooks/use-toast";
import { useAuth } from "@/hooks/useAuth";
import { Loader2, Mail, Lock, User, Eye, EyeOff, Sparkles } from "lucide-react";
import { FormFieldHelper } from "@/components/ui/contextual-tooltip";

interface RegisterData {
  firstName: string;
  lastName: string;
  email: string;
  password: string;
}

export default function ModernAuthForm() {
  const [isLogin, setIsLogin] = useState(true);
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState<RegisterData>({
    firstName: "",
    lastName: "",
    email: "",
    password: "",
  });
  const { toast } = useToast();
  const { loginMutation, registerMutation } = useAuth();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    if (isLogin) {
      loginMutation.mutate({
        email: formData.email,
        password: formData.password,
      });
    } else {
      registerMutation.mutate(formData);
    }
  };

  const isLoading = loginMutation.isPending || registerMutation.isPending;

  return (
    <div className="min-h-screen bg-background flex items-center justify-center relative overflow-hidden">
      {/* Animated background */}
      <div className="absolute inset-0">
        <div className="absolute -top-1/2 -left-1/2 w-full h-full bg-gradient-to-br from-purple-600/20 via-transparent to-blue-600/20 rounded-full blur-3xl animate-pulse-soft" />
        <div className="absolute -bottom-1/2 -right-1/2 w-full h-full bg-gradient-to-tl from-pink-600/20 via-transparent to-cyan-600/20 rounded-full blur-3xl animate-pulse-soft" />
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-gradient-to-br from-green-600/10 to-emerald-600/10 rounded-full blur-3xl animate-pulse-soft" />
      </div>

      <div className="relative z-10 w-full max-w-md mx-auto p-6">
        {/* Logo and Title */}
        <div className="text-center mb-8 animate-fade-in">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 shadow-2xl mb-4">
            <Sparkles className="w-8 h-8 text-white" />
          </div>
          <h1 className="text-3xl font-bold gradient-text mb-2">TransportHub</h1>
          <p className="text-muted-foreground">
            {isLogin ? "Добро пожаловать обратно" : "Создайте новый аккаунт"}
          </p>
        </div>

        {/* Auth Form */}
        <div className="glass-card p-8 rounded-2xl border border-border/30 animate-slide-in">
          <form onSubmit={handleSubmit} className="space-y-6">
            {!isLogin && (
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <div className="flex items-center gap-2">
                    <Label htmlFor="firstName" className="text-sm font-medium text-foreground">
                      Имя
                    </Label>
                    <FormFieldHelper 
                      label="Имя"
                      description="Укажите ваше имя для персонализации системы"
                      required={true}
                    />
                  </div>
                  <div className="relative">
                    <User className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <Input
                      id="firstName"
                      type="text"
                      placeholder="Ваше имя"
                      value={formData.firstName}
                      onChange={(e) =>
                        setFormData({ ...formData, firstName: e.target.value })
                      }
                      className="modern-input pl-10"
                      required
                    />
                  </div>
                </div>
                <div className="space-y-2">
                  <div className="flex items-center gap-2">
                    <Label htmlFor="lastName" className="text-sm font-medium text-foreground">
                      Фамилия
                    </Label>
                    <FormFieldHelper 
                      label="Фамилия"
                      description="Укажите вашу фамилию для полной идентификации"
                      required={true}
                    />
                  </div>
                  <div className="relative">
                    <User className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <Input
                      id="lastName"
                      type="text"
                      placeholder="Ваша фамилия"
                      value={formData.lastName}
                      onChange={(e) =>
                        setFormData({ ...formData, lastName: e.target.value })
                      }
                      className="modern-input pl-10"
                      required
                    />
                  </div>
                </div>
              </div>
            )}

            <div className="space-y-2">
              <div className="flex items-center gap-2">
                <Label htmlFor="email" className="text-sm font-medium text-foreground">
                  Email
                </Label>
                <FormFieldHelper 
                  label="Email"
                  description="Используется для входа в систему и получения уведомлений"
                  example="user@company.com"
                  required={true}
                />
              </div>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  id="email"
                  type="email"
                  placeholder="your@email.com"
                  value={formData.email}
                  onChange={(e) =>
                    setFormData({ ...formData, email: e.target.value })
                  }
                  className="modern-input pl-10"
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <div className="flex items-center gap-2">
                <Label htmlFor="password" className="text-sm font-medium text-foreground">
                  Пароль
                </Label>
                <FormFieldHelper 
                  label="Пароль"
                  description={isLogin ? "Введите ваш пароль для входа в систему" : "Создайте надежный пароль для защиты аккаунта"}
                  example={isLogin ? undefined : "Минимум 6 символов"}
                  required={true}
                />
              </div>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  id="password"
                  type={showPassword ? "text" : "password"}
                  placeholder="Введите пароль"
                  value={formData.password}
                  onChange={(e) =>
                    setFormData({ ...formData, password: e.target.value })
                  }
                  className="modern-input pl-10 pr-10"
                  required
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            <Button
              type="submit"
              disabled={isLoading}
              className="btn-gradient w-full py-3 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300"
            >
              {isLoading ? (
                <>
                  <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                  {isLogin ? "Вход..." : "Регистрация..."}
                </>
              ) : (
                <span>{isLogin ? "Войти" : "Создать аккаунт"}</span>
              )}
            </Button>

            <div className="text-center">
              <button
                type="button"
                onClick={() => setIsLogin(!isLogin)}
                className="text-sm text-muted-foreground hover:text-primary transition-colors duration-200"
              >
                {isLogin
                  ? "Нет аккаунта? Создайте новый"
                  : "Уже есть аккаунт? Войдите"}
              </button>
            </div>
          </form>
        </div>

        {/* Demo Credentials */}
        <div className="mt-6 glass-card p-4 rounded-xl border border-border/20 animate-slide-in" style={{ animationDelay: "0.2s" }}>
          <h3 className="text-sm font-medium text-foreground mb-2">Демо доступ:</h3>
          <div className="text-xs text-muted-foreground space-y-1">
            <p><strong>Email:</strong> test@test.com</p>
            <p><strong>Пароль:</strong> test123</p>
            <p><strong>Роль:</strong> Прораб</p>
          </div>
        </div>
      </div>
    </div>
  );
}