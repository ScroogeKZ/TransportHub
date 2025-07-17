import { useAuth } from "@/hooks/useAuth";
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
  Building2,
  Route,
  Calculator,
  MapPin,
  Menu,
  X,
  Home,
  Settings,
  Crown,
  Bell,
  Search,
} from "lucide-react";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

interface MinimalLayoutProps {
  children: React.ReactNode;
}

export default function MinimalLayout({ children }: MinimalLayoutProps) {
  const { user, logout } = useAuth();
  const [location] = useLocation();
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);

  if (!user) return null;

  const navigation = [
    {
      name: "Главная",
      href: "/",
      icon: Home,
      current: location === "/",
    },
    {
      name: "Заявки",
      href: "/requests",
      icon: Truck,
      current: location === "/requests",
    },
    {
      name: "Создать заявку",
      href: "/create",
      icon: Plus,
      current: location === "/create",
      roles: ["прораб", "логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Перевозчики",
      href: "/carriers",
      icon: Building2,
      current: location === "/carriers",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Маршруты",
      href: "/routes",
      icon: Route,
      current: location === "/routes",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Калькулятор",
      href: "/calculator",
      icon: Calculator,
      current: location === "/calculator",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Отслеживание",
      href: "/tracking",
      icon: MapPin,
      current: location === "/tracking",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Отчеты",
      href: "/reports",
      icon: FileText,
      current: location === "/reports",
      roles: ["руководитель", "финансовый", "генеральный", "супер_админ"],
    },
    {
      name: "Пользователи",
      href: "/users",
      icon: Users,
      current: location === "/users",
      roles: ["генеральный", "супер_админ"],
    },
    {
      name: "Админ панель",
      href: "/super-admin",
      icon: Crown,
      current: location === "/super-admin",
      roles: ["супер_админ"],
    },
  ];

  const filteredNavigation = navigation.filter(item => 
    !item.roles || item.roles.includes(user.role)
  );

  return (
    <div className="min-h-screen bg-background">
      <div className="flex h-screen">
        {/* Sidebar */}
        <div className={`bg-card border-r border-border transition-all duration-300 ${isSidebarOpen ? 'w-64' : 'w-16'}`}>
          <div className="flex flex-col h-full">
            {/* Header */}
            <div className="flex items-center justify-between p-4 border-b border-border">
              <div className={`flex items-center space-x-3 ${!isSidebarOpen && 'hidden'}`}>
                <div className="w-8 h-8 bg-primary rounded-md flex items-center justify-center">
                  <Truck className="w-5 h-5 text-primary-foreground" />
                </div>
                <div>
                  <h1 className="font-semibold text-foreground">Хром-KZ</h1>
                  <p className="text-xs text-muted-foreground">Транспорт</p>
                </div>
              </div>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                className="clean-button"
              >
                {isSidebarOpen ? <X className="w-4 h-4" /> : <Menu className="w-4 h-4" />}
              </Button>
            </div>

            {/* User Profile */}
            <div className={`p-4 border-b border-border ${!isSidebarOpen && 'hidden'}`}>
              <div className="flex items-center space-x-3">
                <Avatar className="w-8 h-8">
                  <AvatarImage src={user.profileImageUrl || ""} />
                  <AvatarFallback className="bg-muted text-foreground text-sm">
                    {user.firstName?.[0]}{user.lastName?.[0]}
                  </AvatarFallback>
                </Avatar>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-foreground truncate">{user.firstName} {user.lastName}</p>
                  <p className="text-xs text-muted-foreground truncate">{user.role}</p>
                </div>
              </div>
            </div>

            {/* Navigation */}
            <nav className="flex-1 p-4 space-y-1">
              {filteredNavigation.map((item) => {
                const Icon = item.icon;
                return (
                  <Link key={item.name} href={item.href}>
                    <div className={`flex items-center space-x-3 px-3 py-2 rounded-md text-sm transition-all duration-200 ${
                      item.current 
                        ? 'bg-primary text-primary-foreground' 
                        : 'text-muted-foreground hover:text-foreground hover:bg-muted'
                    }`}>
                      <Icon className="w-4 h-4" />
                      <span className={`${!isSidebarOpen && 'hidden'}`}>
                        {item.name}
                      </span>
                    </div>
                  </Link>
                );
              })}
            </nav>

            {/* Logout Button */}
            <div className={`p-4 border-t border-border ${!isSidebarOpen && 'hidden'}`}>
              <Button
                onClick={(e) => {
                  e.preventDefault();
                  if (confirm('Вы уверены, что хотите выйти?')) {
                    logout();
                  }
                }}
                variant="ghost"
                className="w-full justify-start text-red-600 hover:text-red-700 hover:bg-red-50"
              >
                <LogOut className="w-4 h-4 mr-2" />
                Выйти
              </Button>
            </div>
          </div>
        </div>

        {/* Main Content */}
        <div className="flex-1 flex flex-col overflow-hidden">
          {/* Top Header */}
          <header className="bg-card border-b border-border px-6 py-4">
            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-4">
                <div className="relative">
                  <Search className="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-muted-foreground" />
                  <input
                    type="text"
                    placeholder="Поиск..."
                    className="minimal-input pl-10 w-64"
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
              <div className="flex items-center space-x-2">
                <Button 
                  variant="ghost" 
                  size="sm" 
                  className="icon-button"
                  onClick={() => alert('Уведомления: Новых уведомлений нет')}
                >
                  <Bell className="w-4 h-4" />
                </Button>
                <Button 
                  variant="ghost" 
                  size="sm" 
                  className="icon-button"
                  onClick={() => alert('Настройки: Функция в разработке')}
                >
                  <Settings className="w-4 h-4" />
                </Button>
              </div>
            </div>
          </header>

          {/* Page Content */}
          <main className="flex-1 overflow-auto p-6">
            <div className="container-minimal">
              {children}
            </div>
          </main>
        </div>
      </div>
    </div>
  );
}