import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
  Truck,
  DollarSign,
  Clock,
  TrendingUp,
  Package,
  Users,
  MapPin,
  Calendar,
  ArrowUpRight,
  ArrowDownRight,
  Star,
  Activity,
  Zap,
  Building2,
  Route,
  BarChart3,
  FileText,
  Target,
  CheckCircle,
  XCircle,
  AlertCircle,
  Plus,
  Eye,
  Edit,
  Trash2,
  Filter,
  Search,
  Download,
  RefreshCw,
  Sparkles,
  Crown,
  Shield,
  Globe,
  Award,
  TrendingDown
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { Link } from "wouter";
import { useAuth } from "@/hooks/useAuth";

export default function UltraModernDashboard() {
  const { user } = useAuth();

  const refreshData = () => {
    window.location.reload();
  };

  const exportData = () => {
    // Создаем CSV с данными заявок
    const csvData = requests?.map(request => ({
      'Номер заявки': request.requestNumber,
      'Откуда': request.fromCity,
      'Куда': request.toCity,
      'Груз': request.cargoType,
      'Вес': request.weight,
      'Статус': request.status,
      'Дата создания': new Date(request.createdAt).toLocaleDateString()
    })) || [];
    
    const csvContent = "data:text/csv;charset=utf-8," + 
      Object.keys(csvData[0] || {}).join(",") + "\n" +
      csvData.map(row => Object.values(row).join(",")).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "transport_requests.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const { data: stats } = useQuery({
    queryKey: ["/api/dashboard/stats"],
    queryFn: async () => {
      const response = await fetch("/api/dashboard/stats");
      if (!response.ok) throw new Error("Failed to fetch stats");
      return response.json();
    },
  });

  const { data: requests } = useQuery({
    queryKey: ["/api/transportation-requests"],
    queryFn: async () => {
      const response = await fetch("/api/transportation-requests");
      if (!response.ok) throw new Error("Failed to fetch requests");
      return response.json();
    },
  });

  const { data: monthlyStats } = useQuery({
    queryKey: ["/api/dashboard/monthly-stats"],
    queryFn: async () => {
      const response = await fetch("/api/dashboard/monthly-stats");
      if (!response.ok) throw new Error("Failed to fetch monthly stats");
      return response.json();
    },
  });

  const statsCards = [
    {
      title: "Всего заявок",
      value: stats?.totalTransportations || 0,
      icon: Truck,
      gradient: "from-purple-500 to-pink-500",
      change: "+12.5%",
      changeType: "increase",
      description: "За последний месяц"
    },
    {
      title: "Активные заявки",
      value: stats?.activeRequests || 0,
      icon: Activity,
      gradient: "from-blue-500 to-cyan-500",
      change: "+3.2%",
      changeType: "increase",
      description: "В процессе обработки"
    },
    {
      title: "Общие затраты",
      value: `₸${stats?.totalExpenses?.toLocaleString() || 0}`,
      icon: DollarSign,
      gradient: "from-green-500 to-emerald-500",
      change: "-2.1%",
      changeType: "decrease",
      description: "Экономия бюджета"
    },
    {
      title: "Средняя стоимость",
      value: `₸${stats?.averageCost?.toLocaleString() || 0}`,
      icon: TrendingUp,
      gradient: "from-orange-500 to-red-500",
      change: "+5.8%",
      changeType: "increase",
      description: "За одну заявку"
    },
  ];

  const quickActions = [
    {
      title: "Создать заявку",
      description: "Новая заявка на транспортировку",
      icon: Plus,
      href: "/create",
      gradient: "from-purple-500 to-pink-500",
      roles: ["прораб", "логист", "руководитель", "финансовый", "генеральный", "супер_админ"]
    },
    {
      title: "Перевозчики",
      description: "Управление перевозчиками",
      icon: Building2,
      href: "/carriers",
      gradient: "from-blue-500 to-cyan-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"]
    },
    {
      title: "Маршруты",
      description: "Оптимизация маршрутов",
      icon: Route,
      href: "/routes",
      gradient: "from-green-500 to-emerald-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"]
    },
    {
      title: "Калькулятор",
      description: "Расчет стоимости",
      icon: Target,
      href: "/calculator",
      gradient: "from-orange-500 to-red-500",
      roles: ["логист", "руководитель", "финансовый", "генеральный", "супер_админ"]
    },
    {
      title: "Отчеты",
      description: "Аналитика и отчеты",
      icon: FileText,
      href: "/reports",
      gradient: "from-indigo-500 to-purple-500",
      roles: ["руководитель", "финансовый", "генеральный", "супер_админ"]
    },
    {
      title: "БОГ АДМИНКА",
      description: "Полный контроль системы",
      icon: Crown,
      href: "/super-admin",
      gradient: "from-red-600 to-red-800",
      roles: ["супер_админ"]
    },
  ];

  const filteredActions = quickActions.filter(action => 
    !action.roles || action.roles.includes(user?.role || "")
  );

  const getStatusColor = (status: string) => {
    switch (status) {
      case "created": return "bg-blue-500";
      case "logistics": return "bg-yellow-500";
      case "manager": return "bg-purple-500";
      case "finance": return "bg-green-500";
      case "approved": return "bg-emerald-500";
      case "rejected": return "bg-red-500";
      case "completed": return "bg-gray-500";
      default: return "bg-gray-500";
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case "created": return "Создана";
      case "logistics": return "Логистика";
      case "manager": return "Менеджер";
      case "finance": return "Финансы";
      case "approved": return "Утверждена";
      case "rejected": return "Отклонена";
      case "completed": return "Завершена";
      default: return status;
    }
  };

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="heading-modern">Добро пожаловать, {user?.firstName}!</h1>
          <p className="subheading-modern">Управляйте транспортными заявками с легкостью</p>
        </div>
        <div className="flex items-center space-x-4">
          <Button onClick={exportData} className="modern-button-secondary">
            <Download className="w-4 h-4 mr-2" />
            Экспорт
          </Button>
          <Button onClick={refreshData} className="modern-button">
            <RefreshCw className="w-4 h-4 mr-2" />
            Обновить
          </Button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {statsCards.map((card, index) => {
          const Icon = card.icon;
          return (
            <div key={index} className="glass-card p-6 hover:scale-105 transition-all duration-300 slide-in">
              <div className="flex items-center justify-between">
                <div className="space-y-2">
                  <p className="text-sm font-medium text-muted-foreground">{card.title}</p>
                  <p className="text-2xl font-bold text-foreground">{card.value}</p>
                  <div className="flex items-center space-x-2">
                    {card.changeType === "increase" ? (
                      <ArrowUpRight className="w-4 h-4 text-green-500" />
                    ) : (
                      <ArrowDownRight className="w-4 h-4 text-red-500" />
                    )}
                    <span className={`text-sm font-medium ${
                      card.changeType === "increase" ? "text-green-500" : "text-red-500"
                    }`}>
                      {card.change}
                    </span>
                  </div>
                  <p className="text-xs text-muted-foreground">{card.description}</p>
                </div>
                <div className={`w-14 h-14 rounded-2xl bg-gradient-to-br ${card.gradient} flex items-center justify-center`}>
                  <Icon className="w-7 h-7 text-white" />
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Quick Actions */}
      <div className="glass-card p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-semibold gradient-text">Быстрые действия</h2>
          <Badge variant="secondary" className="glass-button">
            <Sparkles className="w-3 h-3 mr-1" />
            {filteredActions.length} доступно
          </Badge>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {filteredActions.map((action, index) => {
            const Icon = action.icon;
            return (
              <Link key={index} href={action.href}>
                <div className="glass-card p-4 hover:scale-105 transition-all duration-300 cursor-pointer group">
                  <div className="flex items-start space-x-4">
                    <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${action.gradient} flex items-center justify-center transition-transform duration-300 group-hover:scale-110`}>
                      <Icon className="w-6 h-6 text-white" />
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold text-foreground mb-1">{action.title}</h3>
                      <p className="text-sm text-muted-foreground">{action.description}</p>
                    </div>
                  </div>
                </div>
              </Link>
            );
          })}
        </div>
      </div>

      {/* Recent Requests */}
      <div className="glass-card p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-xl font-semibold gradient-text">Последние заявки</h2>
          <div className="flex items-center space-x-2">
            <Button 
              size="sm" 
              variant="outline" 
              className="glass-button"
              onClick={() => window.location.href = '/requests?filter=true'}
            >
              <Filter className="w-4 h-4 mr-2" />
              Фильтр
            </Button>
            <Link href="/requests">
              <Button size="sm" className="glass-button">
                <Eye className="w-4 h-4 mr-2" />
                Все заявки
              </Button>
            </Link>
          </div>
        </div>

        <div className="space-y-4">
          {requests?.slice(0, 5).map((request: any) => (
            <div key={request.id} className="glass-card p-4 hover:scale-102 transition-all duration-300">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-4">
                  <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                    <Package className="w-5 h-5 text-white" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground">{request.requestNumber}</h3>
                    <p className="text-sm text-muted-foreground">
                      {request.fromCity} → {request.toCity}
                    </p>
                  </div>
                </div>
                <div className="flex items-center space-x-4">
                  <Badge className={`${getStatusColor(request.status)} text-white`}>
                    {getStatusText(request.status)}
                  </Badge>
                  <div className="flex items-center space-x-2">
                    <Link href={`/requests?edit=${request.id}`}>
                      <Button size="sm" variant="outline" className="glass-button">
                        <Edit className="w-3 h-3" />
                      </Button>
                    </Link>
                    <Link href={`/requests?view=${request.id}`}>
                      <Button size="sm" variant="outline" className="glass-button">
                        <Eye className="w-3 h-3" />
                      </Button>
                    </Link>
                  </div>
                </div>
              </div>
              <div className="mt-3 grid grid-cols-3 gap-4 text-sm">
                <div>
                  <p className="text-muted-foreground">Груз:</p>
                  <p className="font-medium">{request.cargoType}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Вес:</p>
                  <p className="font-medium">{request.weight} кг</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Создано:</p>
                  <p className="font-medium">{new Date(request.createdAt).toLocaleDateString()}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Monthly Performance */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="glass-card p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-semibold gradient-text">Производительность</h2>
            <Badge variant="secondary" className="glass-button">
              <Award className="w-3 h-3 mr-1" />
              Отлично
            </Badge>
          </div>
          <div className="space-y-4">
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Завершенные заявки</span>
              <span className="text-sm text-muted-foreground">85%</span>
            </div>
            <Progress value={85} className="h-2" />
            
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Время обработки</span>
              <span className="text-sm text-muted-foreground">92%</span>
            </div>
            <Progress value={92} className="h-2" />
            
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Качество доставки</span>
              <span className="text-sm text-muted-foreground">78%</span>
            </div>
            <Progress value={78} className="h-2" />
          </div>
        </div>

        <div className="glass-card p-6">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-semibold gradient-text">Система</h2>
            <Badge variant="secondary" className="glass-button">
              <Globe className="w-3 h-3 mr-1" />
              Онлайн
            </Badge>
          </div>
          <div className="space-y-4">
            <div className="flex items-center justify-between p-3 rounded-lg bg-green-500/10">
              <div className="flex items-center space-x-3">
                <CheckCircle className="w-5 h-5 text-green-500" />
                <span className="text-sm font-medium">База данных</span>
              </div>
              <span className="text-sm text-green-500">Активна</span>
            </div>
            
            <div className="flex items-center justify-between p-3 rounded-lg bg-green-500/10">
              <div className="flex items-center space-x-3">
                <CheckCircle className="w-5 h-5 text-green-500" />
                <span className="text-sm font-medium">API сервисы</span>
              </div>
              <span className="text-sm text-green-500">Работают</span>
            </div>
            
            <div className="flex items-center justify-between p-3 rounded-lg bg-yellow-500/10">
              <div className="flex items-center space-x-3">
                <AlertCircle className="w-5 h-5 text-yellow-500" />
                <span className="text-sm font-medium">Обновления</span>
              </div>
              <span className="text-sm text-yellow-500">Доступны</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}