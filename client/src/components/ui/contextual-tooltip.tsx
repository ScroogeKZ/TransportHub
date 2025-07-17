import React, { useState, useRef, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { 
  Tooltip, 
  TooltipContent, 
  TooltipProvider, 
  TooltipTrigger 
} from "@/components/ui/tooltip";
import { 
  HelpCircle, 
  Info, 
  AlertCircle, 
  CheckCircle, 
  Lightbulb,
  Zap,
  Target,
  Shield,
  Clock,
  DollarSign
} from "lucide-react";
import { cn } from "@/lib/utils";

export interface ContextualTooltipProps {
  content: string;
  title?: string;
  type?: "info" | "help" | "warning" | "success" | "tip" | "feature" | "security" | "cost" | "time";
  position?: "top" | "bottom" | "left" | "right";
  trigger?: "hover" | "click" | "focus";
  delay?: number;
  maxWidth?: string;
  showArrow?: boolean;
  interactive?: boolean;
  children?: React.ReactNode;
  className?: string;
}

const typeConfig = {
  info: {
    icon: Info,
    color: "text-blue-600 dark:text-blue-400",
    bgColor: "bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/50 dark:to-cyan-900/50",
    borderColor: "border-blue-400 dark:border-blue-500",
    shadowColor: "shadow-blue-500/30 dark:shadow-blue-400/40"
  },
  help: {
    icon: HelpCircle,
    color: "text-purple-600 dark:text-purple-400",
    bgColor: "bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50",
    borderColor: "border-purple-400 dark:border-purple-500",
    shadowColor: "shadow-purple-500/30 dark:shadow-purple-400/40"
  },
  warning: {
    icon: AlertCircle,
    color: "text-amber-600 dark:text-amber-400",
    bgColor: "bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/50 dark:to-orange-900/50",
    borderColor: "border-amber-400 dark:border-amber-500",
    shadowColor: "shadow-amber-500/30 dark:shadow-amber-400/40"
  },
  success: {
    icon: CheckCircle,
    color: "text-green-600 dark:text-green-400",
    bgColor: "bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/50 dark:to-emerald-900/50",
    borderColor: "border-green-400 dark:border-green-500",
    shadowColor: "shadow-green-500/30 dark:shadow-green-400/40"
  },
  tip: {
    icon: Lightbulb,
    color: "text-yellow-600 dark:text-yellow-400",
    bgColor: "bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/50 dark:to-amber-900/50",
    borderColor: "border-yellow-400 dark:border-yellow-500",
    shadowColor: "shadow-yellow-500/30 dark:shadow-yellow-400/40"
  },
  feature: {
    icon: Zap,
    color: "text-indigo-600 dark:text-indigo-400",
    bgColor: "bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/50 dark:to-blue-900/50",
    borderColor: "border-indigo-400 dark:border-indigo-500",
    shadowColor: "shadow-indigo-500/30 dark:shadow-indigo-400/40"
  },
  security: {
    icon: Shield,
    color: "text-red-600 dark:text-red-400",
    bgColor: "bg-gradient-to-br from-red-100 to-pink-100 dark:from-red-900/50 dark:to-pink-900/50",
    borderColor: "border-red-400 dark:border-red-500",
    shadowColor: "shadow-red-500/30 dark:shadow-red-400/40"
  },
  cost: {
    icon: DollarSign,
    color: "text-emerald-600 dark:text-emerald-400",
    bgColor: "bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/50 dark:to-teal-900/50",
    borderColor: "border-emerald-400 dark:border-emerald-500",
    shadowColor: "shadow-emerald-500/30 dark:shadow-emerald-400/40"
  },
  time: {
    icon: Clock,
    color: "text-orange-600 dark:text-orange-400",
    bgColor: "bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/50 dark:to-red-900/50",
    borderColor: "border-orange-400 dark:border-orange-500",
    shadowColor: "shadow-orange-500/30 dark:shadow-orange-400/40"
  }
};

export const ContextualTooltip: React.FC<ContextualTooltipProps> = ({
  content,
  title,
  type = "help",
  position = "top",
  trigger = "hover",
  delay = 300,
  maxWidth = "320px",
  showArrow = true,
  interactive = true,
  children,
  className
}) => {
  const [isVisible, setIsVisible] = useState(false);
  const [shouldShow, setShouldShow] = useState(false);
  const timeoutRef = useRef<NodeJS.Timeout | null>(null);
  const config = typeConfig[type];
  const Icon = config.icon;

  const handleMouseEnter = () => {
    if (trigger === "hover") {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
      timeoutRef.current = setTimeout(() => {
        setShouldShow(true);
        setIsVisible(true);
      }, delay);
    }
  };

  const handleMouseLeave = () => {
    if (trigger === "hover") {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
      setShouldShow(false);
      setIsVisible(false);
    }
  };

  const handleClick = () => {
    if (trigger === "click") {
      setIsVisible(!isVisible);
      setShouldShow(!shouldShow);
    }
  };

  const handleFocus = () => {
    if (trigger === "focus") {
      setShouldShow(true);
      setIsVisible(true);
    }
  };

  const handleBlur = () => {
    if (trigger === "focus") {
      setShouldShow(false);
      setIsVisible(false);
    }
  };

  useEffect(() => {
    return () => {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
    };
  }, []);

  const tooltipContent = (
    <motion.div
      initial={{ opacity: 0, scale: 0.8, y: 4 }}
      animate={{ opacity: 1, scale: 1, y: 0 }}
      exit={{ opacity: 0, scale: 0.8, y: 4 }}
      transition={{ 
        type: "spring", 
        stiffness: 400, 
        damping: 30,
        duration: 0.15
      }}
      className={cn(
        "relative max-w-sm p-4 rounded-xl border-2 shadow-2xl backdrop-blur-sm",
        config.bgColor,
        config.borderColor,
        config.shadowColor,
        "ring-2 ring-white/20 dark:ring-white/10",
        className
      )}
      style={{ maxWidth }}
    >
      <div className="flex items-start gap-3">
        <motion.div
          initial={{ rotate: -10, scale: 0.8 }}
          animate={{ rotate: 0, scale: 1 }}
          transition={{ delay: 0.1, type: "spring", stiffness: 400, damping: 20 }}
          className={cn(
            "flex-shrink-0 mt-0.5 p-2 rounded-lg backdrop-blur-sm",
            config.color,
            "bg-white/20 dark:bg-white/10 shadow-lg"
          )}
        >
          <Icon size={18} className="drop-shadow-sm" />
        </motion.div>
        <div className="flex-1 min-w-0">
          {title && (
            <motion.h4
              initial={{ opacity: 0, x: -10 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.05 }}
              className="text-sm font-bold text-gray-900 dark:text-white mb-2 tracking-wide"
            >
              {title}
            </motion.h4>
          )}
          <motion.p
            initial={{ opacity: 0, x: -10 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.1 }}
            className="text-sm text-gray-800 dark:text-gray-100 leading-relaxed font-medium"
          >
            {content}
          </motion.p>
        </div>
      </div>
      {showArrow && (
        <div className="absolute -bottom-1 left-1/2 transform -translate-x-1/2">
          <div className={cn("w-2 h-2 rotate-45 border-b border-r", config.bgColor, config.borderColor)} />
        </div>
      )}
      {/* Decorative glow effect */}
      <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-white/20 via-transparent to-white/20 opacity-40 pointer-events-none" />
      <div className="absolute -inset-0.5 rounded-xl bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-50 blur-sm pointer-events-none" />
    </motion.div>
  );

  if (children) {
    return (
      <div 
        className="relative inline-block"
        onMouseEnter={handleMouseEnter}
        onMouseLeave={handleMouseLeave}
        onClick={handleClick}
        onFocus={handleFocus}
        onBlur={handleBlur}
      >
        {children}
        <AnimatePresence>
          {shouldShow && (
            <div className="absolute z-50 bottom-full left-1/2 transform -translate-x-1/2 mb-2">
              {tooltipContent}
            </div>
          )}
        </AnimatePresence>
      </div>
    );
  }

  return (
    <TooltipProvider>
      <Tooltip open={isVisible} onOpenChange={setIsVisible}>
        <TooltipTrigger asChild>
          <motion.button
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
            className={cn(
              "inline-flex items-center justify-center w-6 h-6 rounded-full transition-all duration-300",
              "bg-gradient-to-r from-white/20 to-white/10 dark:from-white/10 dark:to-white/5",
              "hover:from-white/40 hover:to-white/20 dark:hover:from-white/20 dark:hover:to-white/10",
              "border border-white/20 dark:border-white/10",
              "shadow-lg backdrop-blur-sm",
              "focus:outline-none focus:ring-2 focus:ring-offset-2",
              config.color,
              config.shadowColor
            )}
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}
            onClick={handleClick}
            onFocus={handleFocus}
            onBlur={handleBlur}
          >
            <motion.div
              animate={{ rotate: [0, 5, -5, 0] }}
              transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
            >
              <Icon size={16} className="drop-shadow-sm" />
            </motion.div>
          </motion.button>
        </TooltipTrigger>
        <TooltipContent 
          side={position}
          className="p-0 bg-transparent border-none shadow-none"
        >
          <AnimatePresence>
            {shouldShow && tooltipContent}
          </AnimatePresence>
        </TooltipContent>
      </Tooltip>
    </TooltipProvider>
  );
};

// Quick tooltip for inline help
export const QuickHelp: React.FC<{
  content: string;
  type?: ContextualTooltipProps["type"];
}> = ({ content, type = "help" }) => {
  return (
    <ContextualTooltip
      content={content}
      type={type}
      trigger="hover"
      delay={200}
      maxWidth="280px"
    />
  );
};

// Feature spotlight tooltip
export const FeatureSpotlight: React.FC<{
  title: string;
  content: string;
  children: React.ReactNode;
}> = ({ title, content, children }) => {
  return (
    <ContextualTooltip
      title={title}
      content={content}
      type="feature"
      trigger="hover"
      delay={100}
      maxWidth="360px"
      interactive={true}
    >
      {children}
    </ContextualTooltip>
  );
};

// Form field helper
export const FormFieldHelper: React.FC<{
  label: string;
  description: string;
  example?: string;
  required?: boolean;
}> = ({ label, description, example, required }) => {
  const content = `${description}${example ? `\n\nПример: ${example}` : ''}${required ? '\n\n* Обязательное поле' : ''}`;

  return (
    <ContextualTooltip
      title={label}
      content={content}
      type={required ? "warning" : "info"}
      trigger="hover"
      delay={150}
      maxWidth="300px"
      interactive={true}
    />
  );
};

export default ContextualTooltip;