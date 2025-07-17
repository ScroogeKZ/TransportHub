import { useState } from "react";
import { useAuth } from "@/hooks/useAuth";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { 
  Eye, 
  EyeOff, 
  Mail, 
  Lock, 
  User, 
  UserPlus,
  LogIn,
  Sparkles,
  ArrowRight,
  CheckCircle,
  AlertCircle,
  Zap,
  Shield,
  Truck,
  BarChart3,
  FileText,
  Crown
} from "lucide-react";
import { useToast } from "@/hooks/use-toast";

export default function UltraModernAuthForm() {
  const { login, register, isLoading } = useAuth();
  const { toast } = useToast();
  const [activeTab, setActiveTab] = useState("login");
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
    firstName: "",
    lastName: "",
    role: "прораб",
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (activeTab === "login") {
        await login(formData.email, formData.password);
        toast({
          title: "Успешный вход",
          description: "Добро пожаловать в систему!",
        });
      } else {
        await register(formData);
        toast({
          title: "Регистрация успешна",
          description: "Аккаунт создан успешно!",
        });
      }
    } catch (error: any) {
      toast({
        title: "Ошибка",
        description: error.message || "Что-то пошло не так",
        variant: "destructive",
      });
    }
  };

  const handleInputChange = (field: string, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const features = [
    {
      icon: Shield,
      title: "Безопасность",
      description: "Надежная защита данных",
      gradient: "from-green-500 to-emerald-500"
    },
    {
      icon: Truck,
      title: "Управление",
      description: "Полный контроль заявок",
      gradient: "from-blue-500 to-cyan-500"
    },
    {
      icon: BarChart3,
      title: "Аналитика",
      description: "Детальные отчеты",
      gradient: "from-purple-500 to-pink-500"
    },
    {
      icon: FileText,
      title: "Документооборот",
      description: "Электронные документы",
      gradient: "from-orange-500 to-red-500"
    }
  ];

  return (
    <div className="min-h-screen bg-background grid-pattern flex items-center justify-center p-4">
      {/* Animated background orbs */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-purple-500/20 blur-3xl floating"></div>
        <div className="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-blue-500/20 blur-3xl floating-delayed"></div>
        <div className="absolute top-1/2 left-1/2 w-60 h-60 rounded-full bg-pink-500/20 blur-3xl floating-slow"></div>
      </div>

      <div className="w-full max-w-6xl grid md:grid-cols-2 gap-8 items-center relative z-10">
        {/* Left Side - Features */}
        <div className="space-y-8 slide-in">
          <div>
            <div className="flex items-center space-x-3 mb-6">
              <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                <Zap className="w-7 h-7 text-white" />
              </div>
              <div>
                <h1 className="text-4xl font-bold gradient-text">Хром-KZ</h1>
                <p className="text-lg text-muted-foreground">Система управления транспортом</p>
              </div>
            </div>
            <p className="text-xl text-foreground/80 mb-8">
              Современная платформа для управления транспортными перевозками с интуитивным интерфейсом и мощными возможностями
            </p>
          </div>

          <div className="grid gap-6">
            {features.map((feature, index) => {
              const Icon = feature.icon;
              return (
                <div key={index} className="flex items-start space-x-4 glass-card p-6 hover:scale-105 transition-all duration-300">
                  <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${feature.gradient} flex items-center justify-center`}>
                    <Icon className="w-6 h-6 text-white" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-lg mb-2">{feature.title}</h3>
                    <p className="text-muted-foreground">{feature.description}</p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Right Side - Auth Form */}
        <div className="scale-in">
          <Card className="glass-card border-0 shadow-2xl">
            <CardHeader className="text-center pb-8">
              <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-4">
                <Crown className="w-8 h-8 text-white" />
              </div>
              <CardTitle className="text-2xl font-bold gradient-text">
                Добро пожаловать
              </CardTitle>
              <p className="text-muted-foreground">
                Войдите в систему или создайте новый аккаунт
              </p>
            </CardHeader>
            <CardContent>
              <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                <TabsList className="grid w-full grid-cols-2 glass-card p-1">
                  <TabsTrigger value="login" className="glass-button">
                    <LogIn className="w-4 h-4 mr-2" />
                    Вход
                  </TabsTrigger>
                  <TabsTrigger value="register" className="glass-button">
                    <UserPlus className="w-4 h-4 mr-2" />
                    Регистрация
                  </TabsTrigger>
                </TabsList>

                <TabsContent value="login" className="space-y-6 mt-6">
                  <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
                      <Label htmlFor="email" className="text-sm font-medium">
                        Email
                      </Label>
                      <div className="relative">
                        <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
                        <Input
                          id="email"
                          type="email"
                          placeholder="Введите email"
                          value={formData.email}
                          onChange={(e) => handleInputChange("email", e.target.value)}
                          className="modern-input pl-10"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="password" className="text-sm font-medium">
                        Пароль
                      </Label>
                      <div className="relative">
                        <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
                        <Input
                          id="password"
                          type={showPassword ? "text" : "password"}
                          placeholder="Введите пароль"
                          value={formData.password}
                          onChange={(e) => handleInputChange("password", e.target.value)}
                          className="modern-input pl-10 pr-10"
                          required
                        />
                        <button
                          type="button"
                          onClick={() => setShowPassword(!showPassword)}
                          className="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground hover:text-foreground"
                        >
                          {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                        </button>
                      </div>
                    </div>

                    <Button type="submit" className="w-full modern-button" disabled={isLoading}>
                      {isLoading ? (
                        <div className="flex items-center space-x-2">
                          <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                          <span>Вход...</span>
                        </div>
                      ) : (
                        <div className="flex items-center space-x-2">
                          <span>Войти</span>
                          <ArrowRight className="w-4 h-4" />
                        </div>
                      )}
                    </Button>
                  </form>

                  <div className="pt-4 border-t border-white/10">
                    <p className="text-sm text-muted-foreground mb-3">Тестовые аккаунты:</p>
                    <div className="space-y-2 text-xs">
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-green-500" />
                        <span>admin@test.com / admin123 (Супер Админ)</span>
                      </div>
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-blue-500" />
                        <span>prоrab@test.com / admin123 (Прораб)</span>
                      </div>
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-purple-500" />
                        <span>logist@test.com / admin123 (Логист)</span>
                      </div>
                    </div>
                  </div>
                </TabsContent>

                <TabsContent value="register" className="space-y-6 mt-6">
                  <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                      <div className="space-y-2">
                        <Label htmlFor="firstName" className="text-sm font-medium">
                          Имя
                        </Label>
                        <Input
                          id="firstName"
                          type="text"
                          placeholder="Имя"
                          value={formData.firstName}
                          onChange={(e) => handleInputChange("firstName", e.target.value)}
                          className="modern-input"
                          required
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="lastName" className="text-sm font-medium">
                          Фамилия
                        </Label>
                        <Input
                          id="lastName"
                          type="text"
                          placeholder="Фамилия"
                          value={formData.lastName}
                          onChange={(e) => handleInputChange("lastName", e.target.value)}
                          className="modern-input"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="email" className="text-sm font-medium">
                        Email
                      </Label>
                      <div className="relative">
                        <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
                        <Input
                          id="email"
                          type="email"
                          placeholder="Введите email"
                          value={formData.email}
                          onChange={(e) => handleInputChange("email", e.target.value)}
                          className="modern-input pl-10"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="password" className="text-sm font-medium">
                        Пароль
                      </Label>
                      <div className="relative">
                        <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground w-4 h-4" />
                        <Input
                          id="password"
                          type={showPassword ? "text" : "password"}
                          placeholder="Введите пароль"
                          value={formData.password}
                          onChange={(e) => handleInputChange("password", e.target.value)}
                          className="modern-input pl-10 pr-10"
                          required
                        />
                        <button
                          type="button"
                          onClick={() => setShowPassword(!showPassword)}
                          className="absolute right-3 top-1/2 transform -translate-y-1/2 text-muted-foreground hover:text-foreground"
                        >
                          {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                        </button>
                      </div>
                    </div>

                    <Button type="submit" className="w-full modern-button" disabled={isLoading}>
                      {isLoading ? (
                        <div className="flex items-center space-x-2">
                          <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                          <span>Создание...</span>
                        </div>
                      ) : (
                        <div className="flex items-center space-x-2">
                          <span>Создать аккаунт</span>
                          <Sparkles className="w-4 h-4" />
                        </div>
                      )}
                    </Button>
                  </form>
                </TabsContent>
              </Tabs>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}