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
  Sparkles,
  Bell,
  Settings,
} from "lucide-react";

interface LayoutProps {
  children: React.ReactNode;
}

export default function ModernLayout({ children }: LayoutProps) {
  const { user, logout } = useAuth();
  const { language, setLanguage, t } = useLanguage();
  const [location] = useLocation();
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  if (!user) return null;

  const navigation = [
    {
      name: t("dashboard"),
      href: "/",
      icon: BarChart3,
      current: location === "/",
      gradient: "from-blue-500 to-purple-600"
    },
    {
      name: t("transportation_requests"),
      href: "/requests",
      icon: Truck,
      current: location === "/requests",
      gradient: "from-green-500 to-blue-500"
    },
    {
      name: t("create_request"),
      href: "/create",
      icon: Plus,
      current: location === "/create",
      roles: ["прораб", "логист", "руководитель", "финансовый", "генеральный", "супер_админ", "супер_юзер"],
      gradient: "from-purple-500 to-pink-500"
    },
    {
      name: "Перевозчики",
      href: "/carriers",
      icon: Building2,
      current: location === "/carriers",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
      gradient: "from-orange-500 to-red-500"
    },
    {
      name: "Маршруты",
      href: "/routes",
      icon: Route,
      current: location === "/routes",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
      gradient: "from-cyan-500 to-blue-500"
    },
    {
      name: "Калькулятор",
      href: "/calculator",
      icon: Calculator,
      current: location === "/calculator",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
      gradient: "from-emerald-500 to-green-500"
    },
    {
      name: "Отслеживание",
      href: "/tracking",
      icon: MapPin,
      current: location === "/tracking",
      roles: ["логист", "руководитель", "финансовый", "генеральный директор", "супер_админ"],
      gradient: "from-yellow-500 to-orange-500"
    },
    {
      name: t("reports"),
      href: "/reports",
      icon: FileText,
      current: location === "/reports",
      roles: ["руководитель", "финансовый", "генеральный директор", "супер_админ"],
      gradient: "from-indigo-500 to-purple-500"
    },
    {
      name: t("user_management"),
      href: "/users",
      icon: Users,
      current: location === "/users",
      roles: ["генеральный директор", "супер_админ"],
      gradient: "from-pink-500 to-rose-500"
    },
    {
      name: "БОГ АДМИНКА",
      href: "/super-admin",
      icon: Shield,
      current: location === "/super-admin",
      roles: ["супер_админ"],
      gradient: "from-red-600 to-black"
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
    <div className="min-h-screen bg-background">
      {/* Animated background */}
      <div className="fixed inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-1/2 -left-1/2 w-full h-full bg-gradient-to-br from-purple-600/10 via-transparent to-blue-600/10 rounded-full blur-3xl animate-pulse-soft" />
        <div className="absolute -bottom-1/2 -right-1/2 w-full h-full bg-gradient-to-tl from-pink-600/10 via-transparent to-cyan-600/10 rounded-full blur-3xl animate-pulse-soft" />
      </div>

      {/* Mobile menu overlay */}
      {isMobileMenuOpen && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div 
            className="fixed inset-0 bg-black/60 backdrop-blur-sm" 
            onClick={() => setIsMobileMenuOpen(false)} 
          />
          <nav className="fixed top-0 left-0 bottom-0 flex flex-col w-5/6 max-w-sm glass-card animate-slide-in-right">
            <div className="flex items-center justify-between px-6 py-6 border-b border-border/30">
              <div className="flex items-center space-x-3">
                <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                  <Sparkles className="w-4 h-4 text-white" />
                </div>
                <h2 className="text-xl font-bold gradient-text">Навигация</h2>
              </div>
              <Button
                variant="ghost"
                size="sm"
                onClick={() => setIsMobileMenuOpen(false)}
                className="hover:bg-white/10 rounded-xl"
              >
                <X className="w-5 h-5" />
              </Button>
            </div>
            <div className="flex-1 px-4 py-6 overflow-y-auto custom-scrollbar">
              <nav className="space-y-2">
                {visibleNavigation.map((item) => {
                  const IconComponent = item.icon;
                  return (
                    <Link
                      key={item.name}
                      href={item.href}
                      className={`group relative flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300 ${
                        item.current
                          ? 'text-white shadow-lg scale-105'
                          : 'text-foreground/70 hover:text-white hover:scale-105'
                      }`}
                      onClick={() => setIsMobileMenuOpen(false)}
                      style={item.current ? {
                        background: `linear-gradient(135deg, ${item.gradient.split(' ')[1]}, ${item.gradient.split(' ')[3]})`
                      } : undefined}
                    >
                      {!item.current && (
                        <div className="absolute inset-0 bg-white/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                      )}
                      <IconComponent className="mr-3 w-5 h-5 relative z-10" />
                      <span className="relative z-10">{item.name}</span>
                    </Link>
                  );
                })}
              </nav>
            </div>
          </nav>
        </div>
      )}

      {/* Desktop Sidebar */}
      <div className="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <div className="flex grow flex-col gap-y-5 overflow-y-auto glass-card m-4 rounded-2xl p-6 custom-scrollbar">
          {/* Logo */}
          <div className="flex items-center space-x-3">
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
              <Sparkles className="w-6 h-6 text-white" />
            </div>
            <div>
              <h1 className="text-xl font-bold gradient-text">TransportHub</h1>
              <p className="text-sm text-muted-foreground">Система логистики</p>
            </div>
          </div>

          {/* Navigation */}
          <nav className="flex flex-1 flex-col space-y-2">
            {visibleNavigation.map((item) => {
              const IconComponent = item.icon;
              return (
                <Link
                  key={item.name}
                  href={item.href}
                  className={`group relative flex items-center px-4 py-3 rounded-xl font-medium transition-all duration-300 ${
                    item.current
                      ? 'text-white shadow-lg scale-105 transform'
                      : 'text-foreground/70 hover:text-white hover:scale-105 hover:transform'
                  }`}
                  style={item.current ? {
                    background: `linear-gradient(135deg, ${item.gradient.split(' ')[1]}, ${item.gradient.split(' ')[3]})`
                  } : undefined}
                >
                  {!item.current && (
                    <div className="absolute inset-0 bg-white/5 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300" />
                  )}
                  <IconComponent className="mr-3 w-5 h-5 relative z-10" />
                  <span className="relative z-10">{item.name}</span>
                  {item.current && (
                    <div className="absolute -right-1 top-1/2 transform -translate-y-1/2 w-1 h-8 bg-white/30 rounded-full" />
                  )}
                </Link>
              );
            })}
          </nav>

          {/* User Profile */}
          <div className="mt-auto">
            <div className="glass-card rounded-xl p-4 border border-border/30">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                  <span className="text-white font-semibold text-sm">
                    {(user as any)?.firstName?.[0]?.toUpperCase() || 'U'}
                  </span>
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-foreground truncate">
                    {(user as any)?.firstName} {(user as any)?.lastName}
                  </p>
                  <p className="text-xs text-muted-foreground truncate">
                    {(user as any)?.role}
                  </p>
                </div>
              </div>
              <div className="mt-3 flex space-x-2">
                <Button
                  variant="ghost"
                  size="sm"
                  className="flex-1 hover:bg-white/10 rounded-lg"
                >
                  <Settings className="w-4 h-4" />
                </Button>
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={logout}
                  className="flex-1 hover:bg-red-500/20 hover:text-red-400 rounded-lg"
                >
                  <LogOut className="w-4 h-4" />
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className="lg:pl-80">
        {/* Top Header */}
        <div className="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-border/30 glass-card px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
          <Button
            variant="ghost"
            size="sm"
            className="-m-2.5 p-2.5 text-foreground lg:hidden rounded-xl hover:bg-white/10"
            onClick={() => setIsMobileMenuOpen(true)}
          >
            <span className="sr-only">Open sidebar</span>
            <Menu className="h-6 w-6" aria-hidden="true" />
          </Button>

          {/* Header Actions */}
          <div className="flex flex-1 gap-x-4 justify-end items-center">
            {/* Language Selector */}
            <Select value={language} onValueChange={setLanguage}>
              <SelectTrigger className="w-32 glass-card border-border/30 rounded-xl">
                <SelectValue>
                  <span className="flex items-center space-x-2">
                    <span>{languageFlags[language]}</span>
                    <span className="hidden sm:inline">{languageNames[language]}</span>
                  </span>
                </SelectValue>
              </SelectTrigger>
              <SelectContent className="glass-card border-border/30">
                {(Object.keys(languageNames) as Language[]).map((lang) => (
                  <SelectItem key={lang} value={lang} className="hover:bg-white/10">
                    <span className="flex items-center space-x-2">
                      <span>{languageFlags[lang]}</span>
                      <span>{languageNames[lang]}</span>
                    </span>
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>

            {/* Notifications */}
            <Button
              variant="ghost"
              size="sm"
              className="relative hover:bg-white/10 rounded-xl"
            >
              <Bell className="h-5 w-5" />
              <span className="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full animate-pulse" />
            </Button>

            {/* User Menu - Mobile */}
            <div className="lg:hidden">
              <Button
                variant="ghost"
                size="sm"
                className="hover:bg-white/10 rounded-xl"
              >
                <div className="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                  <span className="text-white font-semibold text-xs">
                    {(user as any)?.firstName?.[0]?.toUpperCase() || 'U'}
                  </span>
                </div>
              </Button>
            </div>
          </div>
        </div>

        {/* Page Content */}
        <main className="py-6 animate-fade-in">
          <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {children}
          </div>
        </main>
      </div>

      {/* Floating Action Button */}
      <Link
        href="/create"
        className="floating-action flex items-center justify-center hover:scale-110"
      >
        <Plus className="w-6 h-6 text-white" />
      </Link>
    </div>
  );
}