import { useAuth } from "@/hooks/useAuth";
import { useLanguage, type Language } from "@/hooks/useLanguage";
import { useState } from "react";
import { Link, useLocation } from "wouter";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
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
  Home,
  Settings,
  Bell,
  Search,
} from "lucide-react";

interface ModernLayoutProps {
  children: React.ReactNode;
}

export default function ModernLayout({ children }: ModernLayoutProps) {
  const { user, logout } = useAuth();
  const { language, setLanguage, t } = useLanguage();
  const [location] = useLocation();
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  if (!user) return null;

  const navigation = [
    {
      name: "Главная",
      href: "/",
      icon: Home,
      current: location === "/",
    },
    {
      name: t("transportation_requests"),
      href: "/requests",
      icon: Truck,
      current: location === "/requests",
    },
    {
      name: t("create_request"),
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
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
    },
    {
      name: "Маршруты",
      href: "/routes",
      icon: Route,
      current: location === "/routes",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
    },
    {
      name: "Калькулятор",
      href: "/calculator",
      icon: Calculator,
      current: location === "/calculator",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
    },
    {
      name: "Отслеживание",
      href: "/tracking",
      icon: MapPin,
      current: location === "/tracking",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
    },
    {
      name: t("reports"),
      href: "/reports",
      icon: FileText,
      current: location === "/reports",
      roles: ["руководитель", "финансовый", "генеральный директор", "супер_админ"],
    },
    {
      name: t("user_management"),
      href: "/users",
      icon: Users,
      current: location === "/users",
      roles: ["генеральный директор", "супер_админ"],
    },
    {
      name: "БОГ АДМИН",
      href: "/super-admin",
      icon: Shield,
      current: location === "/super-admin",
      roles: ["супер_админ"],
    },
  ];

  const visibleNavigation = navigation.filter(
    (item) => !item.roles || item.roles.includes((user as any)?.role)
  );

  const languageFlags: Record<Language, string> = {
    ru: "🇷🇺",
    kz: "🇰🇿",
    en: "🇺🇸",
  };

  const languageNames: Record<Language, string> = {
    ru: "Русский",
    kz: "Қазақша",
    en: "English",
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
      {/* Top Header */}
      <header className="glass-card fixed top-0 left-0 right-0 z-50 m-4 rounded-2xl">
        <div className="px-6 py-4 flex justify-between items-center">
          <div className="flex items-center space-x-4">
            <Button
              variant="ghost"
              size="sm"
              className="md:hidden text-white hover:bg-white/10"
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
            >
              {isMobileMenuOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
            </Button>
            
            <div className="flex items-center space-x-3">
              <div className="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <Truck className="h-4 w-4 text-white" />
              </div>
              <div>
                <h1 className="text-lg font-bold text-white">
                  {t("transport_registry")}
                </h1>
                <p className="text-sm text-gray-400 hidden sm:block">
                  {t("logistics_system")}
                </p>
              </div>
            </div>
          </div>

          <div className="flex items-center space-x-4">
            {/* Search */}
            <Button variant="ghost" size="sm" className="text-white hover:bg-white/10">
              <Search className="h-4 w-4" />
            </Button>

            {/* Notifications */}
            <Button variant="ghost" size="sm" className="text-white hover:bg-white/10">
              <Bell className="h-4 w-4" />
            </Button>

            {/* Language Switcher */}
            <Select
              value={language}
              onValueChange={(value: Language) => setLanguage(value)}
            >
              <SelectTrigger className="w-32 bg-white/10 border-white/20 text-white">
                <SelectValue>
                  <span className="flex items-center space-x-2">
                    <span>{languageFlags[language]}</span>
                    <span className="hidden sm:inline">{languageNames[language]}</span>
                  </span>
                </SelectValue>
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="ru">🇷🇺 Русский</SelectItem>
                <SelectItem value="kz">🇰🇿 Қазақша</SelectItem>
                <SelectItem value="en">🇺🇸 English</SelectItem>
              </SelectContent>
            </Select>

            {/* User Profile */}
            <div className="flex items-center space-x-3">
              <div className="text-right hidden sm:block">
                <p className="text-sm font-medium text-white">
                  {(user as any)?.firstName || (user as any)?.email || "Пользователь"}
                </p>
                <p className="text-xs text-gray-400">
                  {t((user as any)?.role)}
                </p>
              </div>
              <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-xl flex items-center justify-center text-sm font-medium">
                {((user as any)?.firstName?.[0] || (user as any)?.email?.[0] || "П").toUpperCase()}
              </div>
            </div>

            {/* Logout */}
            <Button
              variant="ghost"
              size="sm"
              onClick={() => logout()}
              className="text-white hover:bg-white/10"
            >
              <LogOut className="h-4 w-4" />
            </Button>
          </div>
        </div>
      </header>

      <div className="flex pt-24">
        {/* Desktop Sidebar */}
        <aside className="hidden md:block w-64 fixed left-0 top-24 bottom-0 p-4">
          <nav className="glass-card h-full p-6 rounded-2xl">
            <div className="space-y-2">
              {visibleNavigation.map((item) => {
                const Icon = item.icon;
                return (
                  <Link key={item.name} href={item.href}>
                    <div
                      className={`flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 cursor-pointer ${
                        item.current
                          ? "bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg"
                          : "text-gray-300 hover:bg-white/10 hover:text-white"
                      }`}
                    >
                      <Icon className="w-5 h-5 flex-shrink-0" />
                      <span className="text-sm font-medium">{item.name}</span>
                    </div>
                  </Link>
                );
              })}
            </div>
          </nav>
        </aside>

        {/* Mobile Menu */}
        {isMobileMenuOpen && (
          <div className="md:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm">
            <div className="glass-card w-64 h-full p-6 rounded-r-2xl">
              <div className="space-y-2">
                {visibleNavigation.map((item) => {
                  const Icon = item.icon;
                  return (
                    <Link key={item.name} href={item.href}>
                      <div
                        className={`flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 cursor-pointer ${
                          item.current
                            ? "bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg"
                            : "text-gray-300 hover:bg-white/10 hover:text-white"
                        }`}
                        onClick={() => setIsMobileMenuOpen(false)}
                      >
                        <Icon className="w-5 h-5 flex-shrink-0" />
                        <span className="text-sm font-medium">{item.name}</span>
                      </div>
                    </Link>
                  );
                })}
              </div>
            </div>
          </div>
        )}

        {/* Main Content */}
        <main className="flex-1 md:pl-64 p-4">
          <div className="glass-card rounded-2xl p-6 min-h-[calc(100vh-8rem)]">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}