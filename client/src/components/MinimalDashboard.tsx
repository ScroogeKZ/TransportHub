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
  MoreVertical,
  TrendingDown
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { Link } from "wouter";
import { useAuth } from "@/hooks/useAuth";

export default function MinimalDashboard() {
  const { user } = useAuth();

  const refreshData = () => {
    window.location.reload();
  };

  const exportData = () => {
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

  const getStatusColor = (status: string) => {
    switch (status) {
      case "created": return "bg-blue-100 text-blue-800";
      case "logistics": return "bg-yellow-100 text-yellow-800";
      case "manager": return "bg-purple-100 text-purple-800";
      case "financial": return "bg-orange-100 text-orange-800";
      case "approved": return "bg-green-100 text-green-800";
      case "rejected": return "bg-red-100 text-red-800";
      default: return "bg-gray-100 text-gray-800";
    }
  };

  const getStatusText = (status: string) => {
    switch (status) {
      case "created": return "Создано";
      case "logistics": return "Логистика";
      case "manager": return "Менеджер";
      case "financial": return "Финансы";
      case "approved": return "Одобрено";
      case "rejected": return "Отклонено";
      default: return status;
    }
  };

  const getStatusIcon = (status: string) => {
    switch (status) {
      case "created": return <AlertCircle className="w-4 h-4" />;
      case "logistics": return <Clock className="w-4 h-4" />;
      case "manager": return <Users className="w-4 h-4" />;
      case "financial": return <DollarSign className="w-4 h-4" />;
      case "approved": return <CheckCircle className="w-4 h-4" />;
      case "rejected": return <XCircle className="w-4 h-4" />;
      default: return <Activity className="w-4 h-4" />;
    }
  };

  return (
    <div className="space-y-6 fade-in">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="minimal-heading">Добро пожаловать, {user?.firstName}!</h1>
          <p className="minimal-subheading">Обзор системы транспортных заявок</p>
        </div>
        <div className="flex items-center space-x-3">
          <Button onClick={exportData} className="secondary-button">
            <Download className="w-4 h-4 mr-2" />
            Экспорт
          </Button>
          <Button onClick={refreshData} className="primary-button">
            <RefreshCw className="w-4 h-4 mr-2" />
            Обновить
          </Button>
        </div>
      </div>

      {/* Quick Actions */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <Link href="/create">
          <Card className="clean-card hover:shadow-md transition-all cursor-pointer">
            <CardContent className="p-4">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <Plus className="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-foreground">Создать заявку</p>
                  <p className="text-xs text-muted-foreground">Новая транспортная заявка</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </Link>

        <Link href="/requests">
          <Card className="clean-card hover:shadow-md transition-all cursor-pointer">
            <CardContent className="p-4">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                  <FileText className="w-5 h-5 text-green-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-foreground">Все заявки</p>
                  <p className="text-xs text-muted-foreground">Просмотр заявок</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </Link>

        <Link href="/carriers">
          <Card className="clean-card hover:shadow-md transition-all cursor-pointer">
            <CardContent className="p-4">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                  <Building2 className="w-5 h-5 text-purple-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-foreground">Перевозчики</p>
                  <p className="text-xs text-muted-foreground">Управление перевозчиками</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </Link>

        <Link href="/reports">
          <Card className="clean-card hover:shadow-md transition-all cursor-pointer">
            <CardContent className="p-4">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                  <BarChart3 className="w-5 h-5 text-orange-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-foreground">Отчеты</p>
                  <p className="text-xs text-muted-foreground">Аналитика и отчеты</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </Link>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <Card className="clean-card">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Всего заявок</p>
                <p className="text-2xl font-semibold text-foreground">{stats?.totalTransportations || 0}</p>
              </div>
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Truck className="w-6 h-6 text-blue-600" />
              </div>
            </div>
            <div className="mt-4 flex items-center text-sm">
              <TrendingUp className="w-4 h-4 text-green-600 mr-1" />
              <span className="text-green-600">+12%</span>
              <span className="text-muted-foreground ml-1">от прошлого месяца</span>
            </div>
          </CardContent>
        </Card>

        <Card className="clean-card">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Общие расходы</p>
                <p className="text-2xl font-semibold text-foreground">{stats?.totalExpenses?.toLocaleString() || 0} ₸</p>
              </div>
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <DollarSign className="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div className="mt-4 flex items-center text-sm">
              <TrendingDown className="w-4 h-4 text-red-600 mr-1" />
              <span className="text-red-600">-5%</span>
              <span className="text-muted-foreground ml-1">от прошлого месяца</span>
            </div>
          </CardContent>
        </Card>

        <Card className="clean-card">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Активные заявки</p>
                <p className="text-2xl font-semibold text-foreground">{stats?.activeTransportations || 0}</p>
              </div>
              <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Clock className="w-6 h-6 text-orange-600" />
              </div>
            </div>
            <div className="mt-4 flex items-center text-sm">
              <Activity className="w-4 h-4 text-blue-600 mr-1" />
              <span className="text-blue-600">В процессе</span>
            </div>
          </CardContent>
        </Card>

        <Card className="clean-card">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-muted-foreground">Одобренные</p>
                <p className="text-2xl font-semibold text-foreground">{stats?.approvedTransportations || 0}</p>
              </div>
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <CheckCircle className="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div className="mt-4 flex items-center text-sm">
              <TrendingUp className="w-4 h-4 text-green-600 mr-1" />
              <span className="text-green-600">+8%</span>
              <span className="text-muted-foreground ml-1">от прошлого месяца</span>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Recent Requests */}
      <Card className="clean-card">
        <CardHeader className="pb-4">
          <div className="flex items-center justify-between">
            <CardTitle className="text-lg font-semibold text-foreground">Последние заявки</CardTitle>
            <div className="flex items-center space-x-2">
              <Button 
                variant="outline" 
                size="sm" 
                className="clean-button"
                onClick={() => window.location.href = '/requests?filter=true'}
              >
                <Filter className="w-4 h-4 mr-2" />
                Фильтр
              </Button>
              <Link href="/requests">
                <Button size="sm" className="primary-button">
                  <Eye className="w-4 h-4 mr-2" />
                  Все заявки
                </Button>
              </Link>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            {requests?.slice(0, 5).map((request: any) => (
              <div key={request.id} className="flex items-center justify-between p-4 bg-muted rounded-lg">
                <div className="flex items-center space-x-4">
                  <div className="w-10 h-10 bg-background rounded-lg flex items-center justify-center">
                    {getStatusIcon(request.status)}
                  </div>
                  <div>
                    <p className="font-medium text-foreground">{request.requestNumber}</p>
                    <p className="text-sm text-muted-foreground">
                      {request.fromCity} → {request.toCity}
                    </p>
                    <p className="text-xs text-muted-foreground">
                      {request.cargoType} • {request.weight} кг
                    </p>
                  </div>
                </div>
                <div className="flex items-center space-x-3">
                  <Badge className={getStatusColor(request.status)}>
                    {getStatusText(request.status)}
                  </Badge>
                  <div className="flex items-center space-x-1">
                    <Link href={`/requests?edit=${request.id}`}>
                      <Button variant="ghost" size="sm" className="icon-button">
                        <Edit className="w-4 h-4" />
                      </Button>
                    </Link>
                    <Link href={`/requests?view=${request.id}`}>
                      <Button variant="ghost" size="sm" className="icon-button">
                        <Eye className="w-4 h-4" />
                      </Button>
                    </Link>
                    <Button variant="ghost" size="sm" className="icon-button">
                      <MoreVertical className="w-4 h-4" />
                    </Button>
                  </div>
                </div>
              </div>
            ))}
          </div>
          
          {!requests?.length && (
            <div className="text-center py-8">
              <Truck className="w-12 h-12 text-muted-foreground mx-auto mb-3" />
              <p className="text-muted-foreground">Заявки не найдены</p>
              <p className="text-sm text-muted-foreground">Создайте первую заявку для начала работы</p>
            </div>
          )}
        </CardContent>
      </Card>

      {/* Monthly Statistics */}
      <Card className="clean-card">
        <CardHeader>
          <CardTitle className="text-lg font-semibold text-foreground">Статистика по месяцам</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="space-y-4">
            {monthlyStats?.map((stat: any) => (
              <div key={stat.month} className="flex items-center justify-between">
                <div className="flex items-center space-x-3">
                  <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <Calendar className="w-4 h-4 text-primary" />
                  </div>
                  <span className="font-medium text-foreground">{stat.month}</span>
                </div>
                <div className="flex items-center space-x-4">
                  <div className="text-right">
                    <p className="text-sm font-medium text-foreground">{stat.count} заявок</p>
                    <p className="text-xs text-muted-foreground">{stat.amount?.toLocaleString()} ₸</p>
                  </div>
                  <div className="w-24">
                    <Progress value={(stat.count / 100) * 100} className="h-2" />
                  </div>
                </div>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}