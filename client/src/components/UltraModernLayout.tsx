import { useAuth } from "@/hooks/useAuth";
import { useLanguage } from "@/hooks/useLanguage";
import { useState } from "react";
import { Link, useLocation } from "wouter";
import { Button } from "@/components/ui/button";
import {
  BarChart3,
  Truck,
  Plus,
  FileText,
  Users,
  LogOut,
  Shield,
  Building2,
  Route,
  Calculator,
  MapPin,
  Menu,
  X,
  Zap,
  Home,
  Settings,
  Crown,
  ChevronRight,
  Bell,
  Search,
} from "lucide-react";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

interface UltraModernLayoutProps {
  children: React.ReactNode;
}

export default function UltraModernLayout({ children }: UltraModernLayoutProps) {
  const { user, logout } = useAuth();
  const { t } = useLanguage();
  const [location] = useLocation();
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  if (!user) return null;

  const navigation = [
    {
      name: "Главная",
      href: "/",
      icon: Home,
      current: location === "/",
      gradient: "from-purple-500 to-pink-500",
    },
    {
      name: "Заявки",
      href: "/requests",
      icon: Truck,
      current: location === "/requests",
      gradient: "from-blue-500 to-cyan-500",
    },
    {
      name: "Создать заявку",
      href: "/create",
      icon: Plus,
      current: location === "/create",
      gradient: "from-green-500 to-emerald-500",
      roles: ["прораб", "логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Перевозчики",
      href: "/carriers",
      icon: Building2,
      current: location === "/carriers",
      gradient: "from-orange-500 to-red-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Маршруты",
      href: "/routes",
      icon: Route,
      current: location === "/routes",
      gradient: "from-indigo-500 to-purple-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Калькулятор",
      href: "/calculator",
      icon: Calculator,
      current: location === "/calculator",
      gradient: "from-teal-500 to-blue-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Отслеживание",
      href: "/tracking",
      icon: MapPin,
      current: location === "/tracking",
      gradient: "from-pink-500 to-rose-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Отчеты",
      href: "/reports",
      icon: FileText,
      current: location === "/reports",
      gradient: "from-amber-500 to-orange-500",
      roles: ["руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Пользователи",
      href: "/users",
      icon: Users,
      current: location === "/users",
      gradient: "from-violet-500 to-purple-500",
      roles: ["генеральный", "супер_админ"],
    },
    {
      name: "БОГ АДМИНКА",
      href: "/super-admin",
      icon: Crown,
      current: location === "/super-admin",
      gradient: "from-red-600 to-red-800",
      roles: ["супер_админ"],
    },
  ];

  const filteredNavigation = navigation.filter(item => 
    !item.roles || item.roles.includes(user.role)
  );

  return (
    <div className="min-h-screen bg-background grid-pattern">
      {/* Animated background orbs */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-purple-500/20 blur-3xl floating"></div>
        <div className="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-blue-500/20 blur-3xl floating-delayed"></div>
        <div className="absolute top-1/2 left-1/2 w-60 h-60 rounded-full bg-pink-500/20 blur-3xl floating-slow"></div>
      </div>

      <div className="flex h-screen">
        {/* Sidebar */}
        <div className={`relative transition-all duration-300 ${isSidebarOpen ? 'w-72' : 'w-20'}`}>
          <div className="fixed top-0 left-0 h-full glass-card m-4 p-6 z-10 overflow-hidden">
            <div className={`transition-all duration-300 ${isSidebarOpen ? 'w-60' : 'w-8'}`}>
              {/* Header */}
              <div className="flex items-center justify-between mb-8">
                <div className={`flex items-center space-x-3 ${!isSidebarOpen && 'hidden'}`}>
                  <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <Zap className="w-6 h-6 text-white" />
                  </div>
                  <div>
                    <h1 className="text-xl font-bold gradient-text">Хром-KZ</h1>
                    <p className="text-xs text-muted-foreground">Транспорт</p>
                  </div>
                </div>
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                  className="glass-button p-2"
                >
                  {isSidebarOpen ? <X className="w-4 h-4" /> : <Menu className="w-4 h-4" />}
                </Button>
              </div>

              {/* User Profile */}
              <div className={`mb-8 ${!isSidebarOpen && 'hidden'}`}>
                <div className="glass-card p-4 hover:scale-105 transition-transform duration-300">
                  <div className="flex items-center space-x-3">
                    <Avatar className="w-10 h-10 ring-2 ring-purple-500/50">
                      <AvatarImage src={user.profileImageUrl || ""} />
                      <AvatarFallback className="bg-gradient-to-br from-purple-500 to-pink-500 text-white">
                        {user.firstName?.[0]}{user.lastName?.[0]}
                      </AvatarFallback>
                    </Avatar>
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-medium truncate">{user.firstName} {user.lastName}</p>
                      <p className="text-xs text-muted-foreground truncate">{user.role}</p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Navigation */}
              <nav className="space-y-2">
                {filteredNavigation.map((item) => {
                  const Icon = item.icon;
                  return (
                    <Link key={item.name} href={item.href}>
                      <div className={`group flex items-center space-x-3 p-3 rounded-xl transition-all duration-300 hover:scale-105 ${
                        item.current 
                          ? 'glass-card neon-glow' 
                          : 'hover:bg-white/5 hover:backdrop-blur-sm'
                      }`}>
                        <div className={`w-8 h-8 rounded-lg bg-gradient-to-br ${item.gradient} flex items-center justify-center transition-transform duration-300 group-hover:scale-110`}>
                          <Icon className="w-4 h-4 text-white" />
                        </div>
                        <span className={`font-medium transition-all duration-300 ${!isSidebarOpen && 'hidden'} ${
                          item.current ? 'text-foreground' : 'text-muted-foreground group-hover:text-foreground'
                        }`}>
                          {item.name}
                        </span>
                        {item.current && isSidebarOpen && (
                          <ChevronRight className="w-4 h-4 text-purple-500 ml-auto" />
                        )}
                      </div>
                    </Link>
                  );
                })}
              </nav>

              {/* Logout Button */}
              <div className={`mt-auto pt-6 ${!isSidebarOpen && 'hidden'}`}>
                <Button
                  onClick={(e) => {
                    e.preventDefault();
                    if (confirm('Вы уверены, что хотите выйти?')) {
                      logout();
                    }
                  }}
                  variant="ghost"
                  className="w-full glass-button hover:bg-red-500/20 text-red-400 hover:text-red-300"
                >
                  <LogOut className="w-4 h-4 mr-2" />
                  Выйти
                </Button>
              </div>
            </div>
          </div>
        </div>

        {/* Main Content */}
        <div className="flex-1 flex flex-col overflow-hidden">
          {/* Top Header */}
          <header className="glass-card m-4 p-6 flex items-center justify-between">
            <div className="flex items-center space-x-4">
              <div className="relative">
                <Search className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground" />
                <input
                  type="text"
                  placeholder="Поиск..."
                  className="modern-input pl-10 w-64"
                  onKeyPress={(e) => {
                    if (e.key === 'Enter') {
                      const searchTerm = e.currentTarget.value;
                      if (searchTerm.trim()) {
                        window.location.href = `/requests?search=${encodeURIComponent(searchTerm)}`;
                      }
                    }
                  }}
                />
              </div>
            </div>
            <div className="flex items-center space-x-4">
              <Button 
                size="sm" 
                className="glass-button relative" 
                onClick={() => alert('Уведомления: Новых уведомлений нет')}
              >
                <Bell className="w-4 h-4" />
                <span className="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
              </Button>
              <Button 
                size="sm" 
                className="glass-button" 
                onClick={() => alert('Настройки: Функция в разработке')}
              >
                <Settings className="w-4 h-4" />
              </Button>
            </div>
          </header>

          {/* Page Content */}
          <main className="flex-1 overflow-auto p-4">
            <div className="container-modern">
              {children}
            </div>
          </main>
        </div>
      </div>
    </div>
  );
}