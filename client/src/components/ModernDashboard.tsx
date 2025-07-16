import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { useLanguage } from "@/hooks/useLanguage";
import { Truck, DollarSign, Clock, Calculator, TrendingUp, Activity, Users, MapPin } from "lucide-react";

export default function ModernDashboard() {
  const { t } = useLanguage();

  const { data: stats, isLoading: statsLoading } = useQuery({
    queryKey: ["/api/dashboard/stats"],
  });

  const { data: recentRequests, isLoading: requestsLoading } = useQuery({
    queryKey: ["/api/transportation-requests"],
  });

  if (statsLoading || requestsLoading) {
    return (
      <div className="flex items-center justify-center min-h-[400px]">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-4 border-primary/30 border-t-primary mx-auto"></div>
          <p className="mt-4 text-lg text-muted-foreground animate-pulse-soft">Загрузка данных...</p>
        </div>
      </div>
    );
  }

  const statCards = [
    {
      title: t("total_transportations"),
      value: (stats as any)?.totalTransportations || 0,
      icon: Truck,
      gradient: "from-blue-500 to-cyan-500",
      change: "+12%",
      trend: "up"
    },
    {
      title: t("total_expenses"),
      value: `₸ ${(stats as any)?.totalExpenses?.toLocaleString() || 0}`,
      icon: DollarSign,
      gradient: "from-green-500 to-emerald-500",
      change: "+8%",
      trend: "up"
    },
    {
      title: t("active_requests"),
      value: (stats as any)?.activeRequests || 0,
      icon: Clock,
      gradient: "from-orange-500 to-red-500",
      change: "-3%",
      trend: "down"
    },
    {
      title: t("average_cost"),
      value: `₸ ${(stats as any)?.averageCost?.toLocaleString() || 0}`,
      icon: Calculator,
      gradient: "from-purple-500 to-pink-500",
      change: "+5%",
      trend: "up"
    }
  ];

  return (
    <div className="space-y-8 animate-fade-in">
      {/* Welcome Header */}
      <div className="glass-card p-8 rounded-2xl border border-border/30">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-4xl font-bold gradient-text mb-2">
              Добро пожаловать!
            </h1>
            <p className="text-lg text-muted-foreground">
              Обзор транспортной системы на сегодня
            </p>
          </div>
          <div className="hidden lg:flex items-center space-x-4">
            <div className="text-right">
              <p className="text-sm text-muted-foreground">Сегодня</p>
              <p className="text-2xl font-bold">
                {new Date().toLocaleDateString('ru-RU', { 
                  day: 'numeric', 
                  month: 'long' 
                })}
              </p>
            </div>
            <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg">
              <Activity className="w-8 h-8 text-white" />
            </div>
          </div>
        </div>
      </div>

      {/* Statistics Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {statCards.map((card, index) => {
          const IconComponent = card.icon;
          return (
            <div
              key={card.title}
              className="glass-card p-6 rounded-2xl border border-border/30 hover:scale-105 transition-all duration-300 animate-slide-in"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="flex items-center justify-between mb-4">
                <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${card.gradient} flex items-center justify-center shadow-lg`}>
                  <IconComponent className="w-6 h-6 text-white" />
                </div>
                <div className={`flex items-center space-x-1 text-sm font-medium ${
                  card.trend === 'up' ? 'text-green-400' : 'text-red-400'
                }`}>
                  <TrendingUp className={`w-4 h-4 ${card.trend === 'down' ? 'rotate-180' : ''}`} />
                  <span>{card.change}</span>
                </div>
              </div>
              <div>
                <p className="text-sm text-muted-foreground mb-1">{card.title}</p>
                <p className="text-2xl font-bold text-foreground">{card.value}</p>
              </div>
            </div>
          );
        })}
      </div>

      {/* Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent Requests */}
        <div className="glass-card p-6 rounded-2xl border border-border/30">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-xl font-semibold text-foreground">Последние заявки</h3>
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
              <Truck className="w-4 h-4 text-white" />
            </div>
          </div>
          
          <div className="space-y-4">
            {(recentRequests as any)?.slice(0, 5).map((request: any, index: number) => (
              <div 
                key={request.id} 
                className="flex items-center justify-between p-4 rounded-xl bg-white/5 hover:bg-white/10 transition-all duration-200 animate-slide-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="flex items-center space-x-3">
                  <div className="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                    <MapPin className="w-5 h-5 text-white" />
                  </div>
                  <div>
                    <p className="font-medium text-foreground">{request.requestNumber}</p>
                    <p className="text-sm text-muted-foreground">
                      {request.fromCity} → {request.toCity}
                    </p>
                  </div>
                </div>
                <div className="text-right">
                  <span className={`status-badge status-${request.status}`}>
                    {request.status}
                  </span>
                  <p className="text-xs text-muted-foreground mt-1">
                    {new Date(request.createdAt).toLocaleDateString('ru-RU')}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Quick Actions */}
        <div className="glass-card p-6 rounded-2xl border border-border/30">
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-xl font-semibold text-foreground">Быстрые действия</h3>
            <div className="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
              <Activity className="w-4 h-4 text-white" />
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <button className="p-4 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 hover:scale-105 transition-all duration-300 text-white">
              <Calculator className="w-6 h-6 mx-auto mb-2" />
              <p className="text-sm font-medium">Калькулятор</p>
            </button>
            <button className="p-4 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 hover:scale-105 transition-all duration-300 text-white">
              <MapPin className="w-6 h-6 mx-auto mb-2" />
              <p className="text-sm font-medium">Отслеживание</p>
            </button>
            <button className="p-4 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 hover:scale-105 transition-all duration-300 text-white">
              <Users className="w-6 h-6 mx-auto mb-2" />
              <p className="text-sm font-medium">Перевозчики</p>
            </button>
            <button className="p-4 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 hover:scale-105 transition-all duration-300 text-white">
              <TrendingUp className="w-6 h-6 mx-auto mb-2" />
              <p className="text-sm font-medium">Отчеты</p>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}