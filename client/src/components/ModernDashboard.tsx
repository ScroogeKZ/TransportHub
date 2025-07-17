import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { useAuth } from "@/hooks/useAuth";
import { useLanguage } from "@/hooks/useLanguage";
import { Link } from "wouter";
import {
  BarChart3,
  TrendingUp,
  Package,
  Clock,
  DollarSign,
  Truck,
  Activity,
  Calendar,
  Users,
  MapPin,
  Plus,
  ArrowRight,
  Zap,
  Shield,
  Globe,
} from "lucide-react";
import { ContextualTooltip, FeatureSpotlight, QuickHelp } from "@/components/ui/contextual-tooltip";
import { Line } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

interface DashboardStats {
  totalTransportations: number;
  totalExpenses: number;
  activeRequests: number;
  averageCost: number;
}

interface MonthlyStats {
  month: string;
  count: number;
  amount: number;
}

interface TransportationRequest {
  id: number;
  requestNumber: string;
  fromCity: string;
  toCity: string;
  cargoType: string;
  status: string;
  createdAt: string;
  estimatedCost: number | null;
}

export default function ModernDashboard() {
  const { user } = useAuth();
  const { t } = useLanguage();

  const getQuickActionTooltip = (title: string) => {
    switch (title) {
      case "Новая заявка":
        return "Создайте новую транспортную заявку с указанием маршрута, типа груза и других деталей. Система автоматически сгенерирует номер заявки.";
      case "Мои заявки":
        return "Просматривайте все свои заявки, отслеживайте статус выполнения, редактируйте детали и следите за прогрессом доставки.";
      case "Перевозчики":
        return "Управляйте базой данных перевозчиков, их контактной информацией, рейтингами и ценовыми предложениями.";
      case "Отчеты":
        return "Получайте детальную аналитику по перевозкам, графики трендов, статистику затрат и KPI показатели.";
      default:
        return "Функция системы управления транспортными заявками.";
    }
  };

  const { data: stats } = useQuery<DashboardStats>({
    queryKey: ["/api/dashboard/stats"],
  });

  const { data: monthlyStats } = useQuery<MonthlyStats[]>({
    queryKey: ["/api/dashboard/monthly-stats"],
  });

  const { data: recentRequests } = useQuery<TransportationRequest[]>({
    queryKey: ["/api/transportation-requests"],
  });

  // Chart data
  const chartData = {
    labels: monthlyStats?.map(item => item.month) || [],
    datasets: [
      {
        label: "Количество перевозок",
        data: monthlyStats?.map(item => item.count) || [],
        borderColor: "rgb(59, 130, 246)",
        backgroundColor: "rgba(59, 130, 246, 0.1)",
        fill: true,
        tension: 0.4,
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
      tooltip: {
        backgroundColor: "rgba(0, 0, 0, 0.8)",
        titleColor: "#fff",
        bodyColor: "#fff",
        borderColor: "rgba(59, 130, 246, 0.5)",
        borderWidth: 1,
      },
    },
    scales: {
      x: {
        grid: {
          display: false,
        },
        ticks: {
          color: "#9ca3af",
        },
      },
      y: {
        grid: {
          color: "rgba(156, 163, 175, 0.1)",
        },
        ticks: {
          color: "#9ca3af",
        },
      },
    },
  };

  const quickActions = [
    {
      title: "Новая заявка",
      description: "Создать транспортный запрос",
      icon: Plus,
      href: "/create",
      color: "from-blue-500 to-blue-600",
    },
    {
      title: "Мои заявки",
      description: "Просмотр всех заявок",
      icon: Package,
      href: "/requests",
      color: "from-purple-500 to-purple-600",
    },
    {
      title: "Перевозчики",
      description: "Управление перевозчиками",
      icon: Truck,
      href: "/carriers",
      color: "from-green-500 to-green-600",
    },
    {
      title: "Отчеты",
      description: "Аналитика и отчеты",
      icon: BarChart3,
      href: "/reports",
      color: "from-orange-500 to-orange-600",
    },
  ];

  return (
    <div className="space-y-6">
      {/* Welcome Section */}
      <div className="glass-card p-6 rounded-2xl bg-gradient-to-br from-blue-500/10 to-purple-600/10">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold text-white mb-2">
              Добро пожаловать, {(user as any)?.firstName || "Пользователь"}!
            </h1>
            <p className="text-gray-300">
              Система управления транспортными запросами
            </p>
          </div>
          <div className="flex items-center space-x-3">
            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
              <Activity className="h-6 w-6 text-white" />
            </div>
            <div className="text-right">
              <p className="text-sm text-gray-400">Роль</p>
              <p className="text-white font-medium">{t((user as any)?.role)}</p>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <FeatureSpotlight
          title="Всего перевозок"
          content="Общее количество завершенных транспортных заявок. Показывает производительность системы и объем работы."
        >
          <Card className="glass-card border-none bg-gradient-to-br from-blue-500/10 to-blue-600/10 hover:from-blue-500/20 hover:to-blue-600/20 transition-all duration-300 cursor-pointer">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-gray-300">
                Всего перевозок
              </CardTitle>
              <Package className="h-4 w-4 text-blue-400" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold text-white">
                {stats?.totalTransportations || 0}
              </div>
              <p className="text-xs text-gray-400 mt-1">
                <TrendingUp className="h-3 w-3 inline mr-1" />
                +12% с прошлого месяца
              </p>
            </CardContent>
          </Card>
        </FeatureSpotlight>

        <FeatureSpotlight
          title="Общие расходы"
          content="Сумма всех затрат на транспортные услуги в тенге. Помогает отслеживать бюджет и планировать расходы."
        >
          <Card className="glass-card border-none bg-gradient-to-br from-green-500/10 to-green-600/10 hover:from-green-500/20 hover:to-green-600/20 transition-all duration-300 cursor-pointer">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-gray-300">
                Общие расходы
              </CardTitle>
              <DollarSign className="h-4 w-4 text-green-400" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold text-white">
                ₸{stats?.totalExpenses?.toLocaleString() || 0}
              </div>
              <p className="text-xs text-gray-400 mt-1">
                <TrendingUp className="h-3 w-3 inline mr-1" />
                +8% с прошлого месяца
              </p>
            </CardContent>
          </Card>
        </FeatureSpotlight>

        <FeatureSpotlight
          title="Активные заявки"
          content="Количество заявок, которые находятся в процессе обработки. Требуют внимания для своевременного выполнения."
        >
          <Card className="glass-card border-none bg-gradient-to-br from-orange-500/10 to-orange-600/10 hover:from-orange-500/20 hover:to-orange-600/20 transition-all duration-300 cursor-pointer">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-gray-300">
                Активные заявки
              </CardTitle>
              <Clock className="h-4 w-4 text-orange-400" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold text-white">
                {stats?.activeRequests || 0}
              </div>
              <p className="text-xs text-gray-400 mt-1">
                <Activity className="h-3 w-3 inline mr-1" />
                В процессе обработки
              </p>
            </CardContent>
          </Card>
        </FeatureSpotlight>

        <FeatureSpotlight
          title="Средняя стоимость"
          content="Средняя стоимость одной перевозки в тенге. Помогает оценить типичные затраты и планировать бюджет."
        >
          <Card className="glass-card border-none bg-gradient-to-br from-purple-500/10 to-purple-600/10 hover:from-purple-500/20 hover:to-purple-600/20 transition-all duration-300 cursor-pointer">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium text-gray-300">
                Средняя стоимость
              </CardTitle>
              <BarChart3 className="h-4 w-4 text-purple-400" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold text-white">
                ₸{stats?.averageCost?.toLocaleString() || 0}
              </div>
              <p className="text-xs text-gray-400 mt-1">
                <TrendingUp className="h-3 w-3 inline mr-1" />
                За одну перевозку
              </p>
            </CardContent>
          </Card>
        </FeatureSpotlight>
      </div>

      {/* Chart and Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Chart */}
        <Card className="glass-card border-none">
          <CardHeader>
            <CardTitle className="text-white">Статистика перевозок</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="h-64">
              <Line data={chartData} options={chartOptions} />
            </div>
          </CardContent>
        </Card>

        {/* Recent Requests */}
        <Card className="glass-card border-none">
          <CardHeader>
            <CardTitle className="text-white">Последние заявки</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              {recentRequests?.slice(0, 5).map((request) => (
                <div key={request.id} className="flex items-center space-x-3 p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                  <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <Package className="h-5 w-5 text-white" />
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-white">
                      {request.requestNumber}
                    </p>
                    <p className="text-xs text-gray-400">
                      {request.fromCity} → {request.toCity}
                    </p>
                  </div>
                  <Badge
                    variant={request.status === "created" ? "default" : "secondary"}
                    className="text-xs"
                  >
                    {request.status}
                  </Badge>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Quick Actions */}
      <div className="glass-card p-6 rounded-2xl">
        <div className="flex items-center gap-2 mb-4">
          <h2 className="text-xl font-bold text-white">Быстрые действия</h2>
          <QuickHelp 
            content="Основные функции системы для быстрого доступа к часто используемым операциям"
            type="info"
          />
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          {quickActions.map((action) => (
            <FeatureSpotlight
              key={action.title}
              title={action.title}
              content={getQuickActionTooltip(action.title)}
            >
              <Link href={action.href}>
                <div className={`p-4 rounded-xl bg-gradient-to-br ${action.color} hover:scale-105 transition-transform cursor-pointer`}>
                  <div className="flex items-center space-x-3">
                    <action.icon className="h-6 w-6 text-white" />
                    <div>
                      <p className="font-medium text-white">{action.title}</p>
                      <p className="text-xs text-white/80">{action.description}</p>
                    </div>
                  </div>
                </div>
              </Link>
            </FeatureSpotlight>
          ))}
        </div>
      </div>
    </div>
  );
}