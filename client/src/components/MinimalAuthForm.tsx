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
  Truck,
  Shield,
  BarChart3,
  FileText,
  CheckCircle,
  ArrowRight
} from "lucide-react";
import { useToast } from "@/hooks/use-toast";

export default function MinimalAuthForm() {
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
      description: "Надежная защита данных"
    },
    {
      icon: Truck,
      title: "Управление",
      description: "Полный контроль заявок"
    },
    {
      icon: BarChart3,
      title: "Аналитика",
      description: "Детальные отчеты"
    },
    {
      icon: FileText,
      title: "Документооборот",
      description: "Электронные документы"
    }
  ];

  return (
    <div className="min-h-screen bg-background flex items-center justify-center p-4">
      <div className="w-full max-w-5xl grid md:grid-cols-2 gap-12 items-center">
        {/* Left Side - Features */}
        <div className="space-y-8 fade-in">
          <div>
            <div className="flex items-center space-x-3 mb-6">
              <div className="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
                <Truck className="w-6 h-6 text-primary-foreground" />
              </div>
              <div>
                <h1 className="text-3xl font-light text-foreground">Хром-KZ</h1>
                <p className="text-muted-foreground">Система управления транспортом</p>
              </div>
            </div>
            <p className="text-xl text-muted-foreground mb-8 font-light">
              Современная платформа для управления транспортными перевозками с интуитивным интерфейсом
            </p>
          </div>

          <div className="grid gap-6">
            {features.map((feature, index) => {
              const Icon = feature.icon;
              return (
                <div key={index} className="flex items-start space-x-4">
                  <div className="w-10 h-10 bg-muted rounded-lg flex items-center justify-center">
                    <Icon className="w-5 h-5 text-foreground" />
                  </div>
                  <div>
                    <h3 className="font-medium text-foreground mb-1">{feature.title}</h3>
                    <p className="text-muted-foreground text-sm">{feature.description}</p>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Right Side - Auth Form */}
        <div className="fade-in">
          <Card className="clean-card border shadow-lg">
            <CardHeader className="text-center pb-6">
              <div className="w-12 h-12 bg-muted rounded-lg flex items-center justify-center mx-auto mb-4">
                <User className="w-6 h-6 text-foreground" />
              </div>
              <CardTitle className="text-2xl font-light text-foreground">
                Добро пожаловать
              </CardTitle>
              <p className="text-muted-foreground text-sm">
                Войдите в систему или создайте новый аккаунт
              </p>
            </CardHeader>
            <CardContent>
              <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                <TabsList className="grid w-full grid-cols-2 bg-muted">
                  <TabsTrigger value="login" className="clean-button">
                    <LogIn className="w-4 h-4 mr-2" />
                    Вход
                  </TabsTrigger>
                  <TabsTrigger value="register" className="clean-button">
                    <UserPlus className="w-4 h-4 mr-2" />
                    Регистрация
                  </TabsTrigger>
                </TabsList>

                <TabsContent value="login" className="space-y-6 mt-6">
                  <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
                      <Label htmlFor="email" className="text-sm font-medium text-foreground">
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
                          className="minimal-input pl-10"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="password" className="text-sm font-medium text-foreground">
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
                          className="minimal-input pl-10 pr-10"
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

                    <Button type="submit" className="w-full primary-button" disabled={isLoading}>
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

                  <div className="divider"></div>
                  <div>
                    <p className="text-sm text-muted-foreground mb-3">Тестовые аккаунты:</p>
                    <div className="space-y-2 text-xs">
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-green-600" />
                        <span>admin@test.com / admin123 (Супер Админ)</span>
                      </div>
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-blue-600" />
                        <span>prоrab@test.com / admin123 (Прораб)</span>
                      </div>
                      <div className="flex items-center space-x-2">
                        <CheckCircle className="w-3 h-3 text-purple-600" />
                        <span>logist@test.com / admin123 (Логист)</span>
                      </div>
                    </div>
                  </div>
                </TabsContent>

                <TabsContent value="register" className="space-y-6 mt-6">
                  <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4">
                      <div className="space-y-2">
                        <Label htmlFor="firstName" className="text-sm font-medium text-foreground">
                          Имя
                        </Label>
                        <Input
                          id="firstName"
                          type="text"
                          placeholder="Имя"
                          value={formData.firstName}
                          onChange={(e) => handleInputChange("firstName", e.target.value)}
                          className="minimal-input"
                          required
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="lastName" className="text-sm font-medium text-foreground">
                          Фамилия
                        </Label>
                        <Input
                          id="lastName"
                          type="text"
                          placeholder="Фамилия"
                          value={formData.lastName}
                          onChange={(e) => handleInputChange("lastName", e.target.value)}
                          className="minimal-input"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="email" className="text-sm font-medium text-foreground">
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
                          className="minimal-input pl-10"
                          required
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="password" className="text-sm font-medium text-foreground">
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
                          className="minimal-input pl-10 pr-10"
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

                    <Button type="submit" className="w-full primary-button" disabled={isLoading}>
                      {isLoading ? (
                        <div className="flex items-center space-x-2">
                          <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin" />
                          <span>Создание...</span>
                        </div>
                      ) : (
                        <div className="flex items-center space-x-2">
                          <span>Создать аккаунт</span>
                          <User className="w-4 h-4" />
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