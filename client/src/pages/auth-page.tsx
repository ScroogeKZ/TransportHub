import { useAuth } from "@/hooks/useAuth";
import MinimalAuthForm from "@/components/MinimalAuthForm";
import { useEffect } from "react";
import { useLocation } from "wouter";

export default function AuthPage() {
  const { user } = useAuth();
  const [, setLocation] = useLocation();

  useEffect(() => {
    if (user) {
      setLocation("/");
    }
  }, [user, setLocation]);

  return <MinimalAuthForm />;
}